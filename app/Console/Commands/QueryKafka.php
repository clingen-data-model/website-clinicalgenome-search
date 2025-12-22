<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Stream;
use App\Pmid;
use App\Curation;
use App\Packet;

class QueryKafka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:kafka {topic}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // gene_dosage, gene_dosage_raw, gene_dosage_sepio_in.

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topic = $this->argument('topic');

        if ($topic === null)
        {
            echo "Topic not found \n";
            exit;
        }

        $stream = Stream::name($topic)->first();

        if ($stream === null)
        {
            echo "Topic not found \n";
            exit;
        }

        $offset = $stream->offset;

        $conf = new \RdKafka\Conf();

        // Set a rebalance callback to log partition assignments (optional)
        $conf->setRebalanceCb(function (\RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) use ($offset) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    //echo "Assign: ";
                    //var_dump($partitions);
                    $kafka->assign($partitions);

                    foreach ($partitions as $tp) {
                        $tp->setOffset($offset);
                        $kafka->commit([$tp]);
                    }
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    // echo "Revoke: ";
                    //var_dump($partitions);
                    $kafka->assign(NULL);
                    break;
                default:
                    throw new \Exception($err);
            }
        });

        // Configure the group.id. All consumer with the same group.id will consume
        // different partitions.
        if ($topic == 'all-curation-events') //  || $topic == 'actionability')
            $conf->set('group.id', 'web_stage');
        else
            $conf->set('group.id', 'web_prod');

        $conf->set('security.protocol', 'sasl_ssl');
        $conf->set('sasl.mechanism', 'PLAIN');
        $conf->set('sasl.username', $stream->username);
        $conf->set('sasl.password', $stream->password);

        // Initial list of Kafka brokers
        $conf->set('metadata.broker.list', $stream->endpoint);
        // Set where to start consuming messages when there is no initial offset in
        // offset store or the desired offset is out of range.
        // 'earliest': start from the beginning
        $conf->set('auto.offset.reset', 'earliest');
        //$conf->set('enable.auto.commit', 'false');


        $consumer = new \RdKafka\KafkaConsumer($conf);

        /*$availableTopics = $consumer->getMetadata(true, null, 60e3)->getTopics();
        echo "Available Topics: \n";
        foreach ($availableTopics as $idx => $avlTopic) {
            echo $idx.': '.$avlTopic->getTopic()."\n";
        }*/

        //$a = $consumer->getCommittedOffsets([new \RdKafka\TopicPartition('gene_dosage', 0)], 60*1000);
        //$low = $high = 0;

        //$consumer->queryWatermarkOffsets('web-group-events', 0, $low, $high, 60*1000);
        //dd($high);

        // Subscribe to topic 'test'
        $consumer->subscribe([$stream->topic]);

        //echo "Waiting for partition assignment... (make take some time when\n";
        //echo "quickly re-joining the group after leaving it.)\n";

        //echo "Reading...\n";
        while (true) {
            $message = $consumer->consume(45 * 1000);

            if ($message === null) {
                // Should not normally happen, but just continue polling
                continue;
            }

            switch ($message->err) {

                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $m = new Packet([
                        'topic'     => $stream->topic,
                        'type'      => Packet::TYPE_KAFKA,
                        'uuid'      => $message->key,
                        'offset'    => $message->offset,
                        'timestamp' => $message->timestamp,
                        'payload'   => $message->payload,
                        'status'    => Packet::STATUS_ACTIVE,
                    ]);

                    $m->save();

                    // Topic-specific handling (unchanged)
                    if (
                        $topic === 'dosage' ||
                        $topic === 'actionability' ||
                        $topic === 'gene-validity' ||
                        $topic === 'variant_interpretation'
                    ) {
                        $payload = json_decode($message->payload);
                        $parser  = $stream->parser;
                        $parser($message, $m);

                        $stream->update(['offset' => $message->offset + 1]);

                    } elseif (
                        $topic === 'gpm-general-events' ||
                        $topic === 'gpm-person-events' ||
                        $topic === 'gpm-gene-events' ||
                        $topic === 'gpm-checkpoint-events'
                    ) {
                        $payload = json_decode($message->payload, true);
                        $parser  = $stream->parser;
                        $parser($payload, $message->timestamp);

                        $stream->offset++;
                        $stream->save();

                    } else {
                        $payload = json_decode($message->payload);
                        $parser  = $stream->parser;
                        $parser($payload);

                        $stream->update(['offset' => $message->offset + 1]);
                    }

                    break;

                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    // Expected when topic is quiet — DO NOT EXIT
                    continue 2;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    // Reached end of partition — wait for new messages
                    continue 2;

                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }


        echo "Update Complete\n";
    }
}
