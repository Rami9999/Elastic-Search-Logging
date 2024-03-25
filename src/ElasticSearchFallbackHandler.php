<?php

namespace Zeour\ElasticSearchLogging;
use Illuminate\Support\Str;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Elastic\Elasticsearch\ClientBuilder;
use Carbon\Carbon;
use Log;
use Monolog\Formatter\ElasticsearchFormatter;

class ElasticsearchFallbackHandler extends AbstractProcessingHandler
{
    protected $elasticsearchHandler;
    protected $fallbackHandler;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);

    }

    protected function write(array $record): void
    {
        try{
            $certificatePath = env('CERTIFICATE_PATH');
            $elasticHost = env('ELASTIC_HOST');
            $today = Carbon::now()->format('Y-m-d');
            $elasticsearchClient = ClientBuilder::create()
            ->setHosts([$elasticHost])
            ->setSSLVerification(false)
            ->setSSLCert($certificatePath.'ca.crt')
            ->setBasicAuthentication(env('ELASTIC_SEARCH_USER_NAME'), env('ELASTIC_SEARCH_PASSWORD'))
            ->setSSLKey($certificatePath.'ca.key')
            ->build();

            $formatter = new ElasticsearchFormatter(env('ELASTIC_LOGS_INDEX').'-'.$today, $this->generateRandomString());

            $this->elasticsearchHandler = new ElasticsearchHandler(
                $elasticsearchClient,
                [] // Options array should be passed here
            );

            $this->elasticsearchHandler->setFormatter($formatter);

        if ($this->fallbackHandler) {
            // Write the log record to the fallback handler
            $this->fallbackHandler->handle($record);
        } elseif ($this->elasticsearchHandler) {
            // Write the log record to the Elasticsearch handler
            $this->elasticsearchHandler->handle($record);
        } else {
            // Log a message indicating that both handlers are not available
            error_log('Both Elasticsearch and fallback handlers are not available.');
        }
    }catch(\Exception $ex)
    {
        $message = print_r($record['message'], true);

        // Replace newline characters in the flattened message
        $message = str_replace(["\r", "\n"], ' ', $message);

        // Update the message field in the record
        $record['message'] = $message;
        $formatted = str_replace(["\r", "\n"], ' ', $record['formatted']);

        // Update the 'formatted' field
        $record['formatted'] = $formatted;
        // Encode the log entry into JSON
        $log = [
            '_index' => env('ELASTIC_LOGS_INDEX') . '-' . $today,
            '_id' => $this->generateRandomString(),
            '_score' => 1,
            '_source' => $record
        ];
        // Encode the log entry into JSON string with pretty print format
        $jsonString = json_encode($log);
            
        Log::channel('single')->info(print_r($jsonString,true));
    }
}

function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
     }
     return $randomString;
    }
}


