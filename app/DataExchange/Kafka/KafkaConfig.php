<?php

namespace App\DataExchange\Kafka;

use Illuminate\Support\Facades\Log;
use App\DataExchange\Exceptions\StreamingServiceException;
use App\DataExchange\Kafka\KafkaEnvValidator;

class KafkaConfig
{
    private $conf;

    public function __construct(KafkaEnvValidator $validator, $group = null)
    {
        $validator();
        $this->conf = new \RdKafka\Conf();
        $this->setGroup($group);

        // Initial list of Kafka brokers
        $this->conf->set('metadata.broker.list', config("dx.broker"));
        $this->conf->set('log_level', (string) LOG_DEBUG);

        // security config
        if (!app()->environment('testing')) {
            $this->configSecurity();
        }
        
        $this->conf->setErrorCb(function ($kafka, $err, $reason) {
            throw new StreamingServiceException("Kafka producer error: ".rd_kafka_err2str($err)." (reason: ".$reason.')');
        });

        $this->conf->setStatsCb(function ($kafka, $json, $json_len) {
            Log::info('Kafka Stats ', json_decode($json));
        });

        $this->conf->setDrMsgCb(function ($kafka, $message) {
            if ($message->err) {
                throw new StreamingServiceException('DrMsg: '.rd_kafka_err2str($message->err));
            }
        });
    }
    public function setGroup($group = null)
    {
        $group = $group ? $group : config('dx.group', 'unc_staging');
        $this->conf->set('group.id', $group);
    }

    public function setRebalanceCallback($callback)
    {
        $this->conf->setRebalanceCb($callback);
        return $this;
    }

    public function getConfig()
    {
        return $this->conf;
    }

    private function configSecurity()
    {
        if (config('dx.broker') == 'exchange.clinicalgenome.org:9093') {
            $this->conf->set('security.protocol', 'ssl');
            $this->conf->set('ssl.certificate.location', config('dx.cert-location'));
            $this->conf->set('ssl.key.location', config('dx.key-location'));
            $this->conf->set('ssl.ca.location', config('dx.ca-location', '/etc/pki/ca-trust/extracted/openssl/ca-kafka-cert'));

            if (config('dx.ssl-key-password', null)) {
                $this->conf->set('ssl.key.password', config('dx.ssl-key-password', null));
            }

            return;
        }
        
        $this->conf->set('security.protocol', 'sasl_ssl');
        $this->conf->set('sasl.mechanism', 'PLAIN');
        $this->conf->set('sasl.username', config('dx.username'));
        $this->conf->set('sasl.password', config('dx.password'));
    }
}
