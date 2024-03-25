<?php

namespace Zeour\ElasticSearchLogging;

use Zeour\ElasticSearchLogging\ElasticsearchFallbackHandler;
use Monolog\Logger;

class ElasticsearchLogger
{
    /**
     * Create a custom Monolog instance.
     */
    public function __invoke(array $config): Logger
    {
        try {
            $logger = new Logger('elasticsearch_fallback');
            $logger->pushHandler(new ElasticsearchFallbackHandler());

            return $logger;
        } catch (\Exception $e) {

        }
    }
}
