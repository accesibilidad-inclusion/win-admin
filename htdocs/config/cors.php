<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'supportsCredentials' => true,
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['DNT','User-Agent','X-Requested-With','If-Modified-Since','Cache-Control','Content-Type','Range'],
    'allowedMethods' => ['GET', 'POST', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 0,
];
