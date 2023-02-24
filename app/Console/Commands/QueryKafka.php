<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Stream;
use App\Pmid;
use App\Curation;

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

        $availableTopics = $consumer->getMetadata(true, null, 60e3)->getTopics();
        echo "Available Topics: \n";
        foreach ($availableTopics as $idx => $avlTopic) {
            echo $idx.': '.$avlTopic->getTopic()."\n";
        }

        //$a = $consumer->getCommittedOffsets([new \RdKafka\TopicPartition('gene_dosage', 0)], 60*1000);
        //$low = $high = 0;

        //$consumer->queryWatermarkOffsets('web-group-events', 0, $low, $high, 60*1000);
        //dd($high);

        // Subscribe to topic 'test'
        $consumer->subscribe([$stream->topic]);

        //echo "Waiting for partition assignment... (make take some time when\n";
        //echo "quickly re-joining the group after leaving it.)\n";

        echo "Reading...\n";
        while (true) {
            $message = $consumer->consume(120*1000);
            //echo $message->err . "\n";

            switch ($message->err) {
                case 0:
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                        if ($topic == "dosage")
                        {
                            $payload = $message;
                        }
                        else
                        {
                            $payload = json_decode($message->payload);
                            //echo $message->offset . "\n";
                            //dd($payload);
                        }
                        $a = $stream->parser;
                        $a($payload);
                        $stream->update(['offset' => $message->offset + 1]);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break 2;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";
                    break 2;
                default:
                    throw new \Exception($message->errstr(), $message->err);
                    break;
            }
        }

		echo "Update Complete\n";
	}
}
