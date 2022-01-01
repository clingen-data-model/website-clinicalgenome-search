<?php

namespace App\DataExchange;

use ReflectionClass;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use App\DataExchange\Kafka\KafkaConfig;
use App\DataExchange\Kafka\KafkaConsumer;
use App\DataExchange\Kafka\KafkaProducer;
use App\DataExchange\Contracts\MessagePusher;
use App\DataExchange\Contracts\MessageConsumer;
use App\Contracts\GeneValidityCurationUpdateJob;
use App\DataExchange\MessagePushers\MessageLogger;
use App\Listeners\UpdateGciCurationFromGveMessage;
use App\DataExchange\MessagePushers\DisabledPusher;
use App\Jobs\UpdateCurationFromGeneValidityMessage;
use App\DataExchange\MessageFactories\MessageFactoryInterface;
use App\DataExchange\MessageFactories\PrecurationV1MessageFactory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class DataExchangeServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\DataExchange\Events\Created::class => [
            \App\Listeners\StreamMessages\PushMessage::class
        ],
        \App\DataExchange\Events\Received::class => [
            \App\Listeners\Curations\UpdateFromStreamMessage::class,
            UpdateGciCurationFromGveMessage::class
        ],
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->loadCommands(__DIR__.'/Commands');
        }

        $this->bindInstances();
    }

    protected function bindInstances()
    {
        $this->app->bind(MessagePusher::class, function () {
            if (!config('dx.push-enable')) {
                return new DisabledPusher();
            }
            if (config('dx.driver') == 'kafka') {
                return $this->app->make(KafkaProducer::class);
            }
            if (config('dx.driver') == 'log') {
                return new MessageLogger();
            }
            
            \Log::warning('No DataExchange driver set.  Defaulting to log driver');
            return new MessageLogger();
        });

        $this->app->bind(\RdKafka\Producer::class, function () {
            $config = $this->app->make(KafkaConfig::class)->getConfig();

            return new \RdKafka\Producer($config);
        });

        $this->app->bind(\RdKafka\KafkaConsumer::class, function () {
            $conf = $this->app->make(KafkaConfig::class)->getConfig();
            $conf->set('auto.offset.reset', 'smallest');

            return new \RdKafka\KafkaConsumer($conf);
        });

        $this->app->bind(MessageConsumer::class, function () {
            return $this->app->make(KafkaConsumer::class);
        });

        $this->app->bind(GeneValidityCurationUpdateJob::class, UpdateCurationFromGeneValidityMessage::class);
        $this->app->bind(MessageFactoryInterface::class, PrecurationV1MessageFactory::class);
    }


    /**
     * Register all of the commands in the given directory.
     *
     * @param  array|string  $paths
     * @return void
     */
    protected function loadCommands($paths)
    {
        $paths = array_unique(Arr::wrap($paths));
        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        $namespace = $this->app->getNamespace();

        $commands = [];
        foreach ((new Finder)->in($paths)->files() as $command) {
            $command = $namespace.str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($command->getPathname(), realpath(app_path()).DIRECTORY_SEPARATOR)
            );

            if (is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()) {
                $commands[] = $command;
            }
        }
        $this->commands($commands);
    }
}
