<?php

return [

    'title' => 'OpenAPI documentation.',
    'description' => 'Auto OpenAPI documentation.',
    'version' => "1.0",

    'servers' => [
        [
            'url' => 'http://localhost',
            'description' => 'Server description',
        ],
    ],

    'responses' => [
        \Setrest\OAPIDocumentation\Router\ResponseFinders\ArrayFinder::class,
    ],

    'api_middleware' => 'api',

    'storage_path' => null,

    'hide_head' => true,
];
