<h1>ELastic search Logging pkg</h1>
###########################################################

<h2>require the pkg: </h2>
<h3> run: composer require zeour/elastic-search-logging</h3>
###########################################################
<h2>Configurations: </h2>

<h3>In config/app add to providers:</h3>
<h4>Zeour\ElasticSearchLogging\ElasticSearchLoggingServiceProvider::class,</h4>
<br>
<h3>in config/logging: </h3>
<h4>add the channel elasticsearch_fallback: </h4>
    'elasticsearch_fallback' => [
        'driver' => 'custom',
        'via' => Zeour\ElasticSearchLogging\ElasticsearchLogger::class,
        'handler'=>Zeour\ElasticSearchLogging\ElasticsearchFallbackHandler::class,
        'level' => 'debug'
    ],
<h4>then add it to stach channels...</h4>
<h4>update single channnel: </h4>
        'single' => [
            'driver' => 'single',
            'path' => storage_path('alternativeLogs/laravel.log'),
            'level' => 'debug',
        ],
<br>
<h3> in .env file, add the folowing variables: </h3>
<b4>
<h4>Elastic Search Authentication: </h4>
<h4>ELASTIC_SEARCH_USER_NAME</h4>
<h4>ELASTIC_SEARCH_PASSWORD</h4>
##############################
<h4>Elastic Search Index: </h4>
<h4>ELASTIC_LOGS_INDEX</h4>
##############################
<h4>Elastic Search ip host: </h4>
<h4>ELASTIC_HOST</h4>
##############################
<h4>Full path to the certificates: </h4>
<h4>CERTIFICATE_PATH</h4>
###############################
<h3>and name the certificates as folowing:</h3>
<h4>ca.crt</h4>
<h4>ca.key</h4>


