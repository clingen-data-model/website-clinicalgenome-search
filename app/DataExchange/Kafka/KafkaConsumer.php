<?php
declare(strict_types=1);

namespace App\DataExchange\Kafka;

use App\DataExchange\Contracts\MessageConsumer;
use  App\DataExchange\Kafka\ErrorMessageHandler;
use  App\DataExchange\Kafka\NoNewMessageHandler;
use  App\DataExchange\Kafka\StoreMessageHandler;
use Illuminate\Contracts\Events\Dispatcher;
use App\DataExchange\Exceptions\StreamingServiceException;
use  App\DataExchange\Kafka\NoActionMessageHandler;
use  App\DataExchange\Kafka\SuccessfulMessageHandler;
use App\DataExchange\Exceptions\StreamingServiceEndOfFIleException;

/**
 * @property array $topics
 */
class KafkaConsumer implements MessageConsumer
{
    protected $kafkaConsumer;
    protected $topics = [];
    protected $listening = false;
    protected $eventDispatcher;

    public function __construct(\RdKafka\KafkaConsumer $kafkaConsumer, Dispatcher $eventDispatcher)
    {
        $this->kafkaConsumer = $kafkaConsumer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __get($key)
    {
        if ($key == 'topics') {
            return $this->topics;
        }
    }

    /**
     * Add a topic to consume
     *
     * @param string $topicName Name of topic to add
     * @return MessageConsumer
     */
    public function addTopic(String $topicName):MessageConsumer
    {
        array_push($this->topics, $topicName);
        $this->cleanTopics();
        return $this;
    }

    /**
     * Remove topic from topic list
     *
     * @param string $topicName Name of topic to remove from topic list
     * @return MessageConsumer
     */
    public function removeTopic(String $topicName):MessageConsumer
    {
        if (in_array($topicName, $this->topics)) {
            unset($this->topics[array_search($topicName, $this->topics)]);
            $this->cleanTopics();
        }

        return $this;
    }

    /**
     * Get a list of topics currently being consumed
     *
     * @retrun array List of topic names
     */
    public function listTopics(): array
    {
        $availableTopics = $this->kafkaConsumer->getMetadata(true, null, 60e3)->getTopics();

        return array_map(
            function ($topic) {
                return [
                    'name' => $topic->getName(),
                    'offset' => $topic->getOffset()
                ];
            },
            $availableTopics
        );
    }
    

    public function listen(): MessageConsumer
    {
        $this->kafkaConsumer->subscribe($this->topics);
    
        $handlerChain = $this->getMessageHandlerChain();
        while (true) {
            $message = $this->kafkaConsumer->consume(10000);
            // if ($message->err == 0) {
            //     \Log::debug(['offset'=> $message->offset, 'err_code' => $message->err, 'payload' => $message->payload]);
            // }
            try {
                $handlerChain->handle($message);
            } catch (StreamingServiceEndOfFIleException $e) {
                continue;
            } catch (StreamingServiceException $th) {
                report($th);
            }
        }

        return $this;
    }

    /**
     * Begin listening for messages on topics in topic list
     *
     * @return MessageConsumer
     */
    public function consume(): MessageConsumer
    {
        $this->kafkaConsumer->subscribe($this->topics);
    
        $handlerChain = $this->getMessageHandlerChain();

        while (true) {
            $message = $this->kafkaConsumer->consume(10000);
            try {
                $handlerChain->handle($message);
            } catch (StreamingServiceEndOfFIleException $e) {
                break;
            } catch (StreamingServiceException $th) {
                report($th);
            }
        }

        return $this;
    }

    public function consumeSomeMessages($numberOfMessages): MessageConsumer
    {
        $this->kafkaConsumer->subscribe($this->topics);

        $handlerChain = $this->getMessageHandlerChain();

        $count = 0;
        while (true) {
            if ($count >= $numberOfMessages) {
                break;
            }
            $message = $this->kafkaConsumer->consume(10000);
            try {
                $handlerChain->handle($message);
                $count++;
            } catch (StreamingServiceEndOfFIleException $e) {
                break;
            } catch (StreamingServiceException $th) {
                report($th);
            }
        }


        return $this;
    }

    private function getMessageHandlerChain()
    {
        $noActionHandler = new NoActionMessageHandler();
        $storeMessageHandler = new StoreMessageHandler();
        $successHandler = new SuccessfulMessageHandler($this->eventDispatcher);
        $errorMessageHandler = new ErrorMessageHandler();
        $noNewMessageHandler = new NoNewMessageHandler();

        $noActionHandler->setNext($noNewMessageHandler)
            ->setNext($storeMessageHandler)
            ->setNext($successHandler)
            ->setNext($errorMessageHandler);

        $chainHead = $noActionHandler;

        return $chainHead;
    }

    /**
     * Make sure topic list is unique.
     */
    private function cleanTopics()
    {
        $this->topics = array_unique($this->topics);
        $this->topics = array_values($this->topics);
    }
}
