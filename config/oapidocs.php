<?php

return [

    'title' => 'OpenAPI documentation.',
    'description' => 'Auto generated apip documentation',
    'version' => "1.0",

    'servers' => [
        [
            'url' => 'http://localhost',
            'description' => 'Server description',
        ],
    ],

    'responses' => [
        \Setrest\OAPIDocumentation\Router\ResponseFinders\ArrayFinder::class,
        \Setrest\OAPIDocumentation\Router\ResponseFinders\ResourceFinder::class,
    ],

    'api_middleware' => 'api',

    'storage_path' => null,

    'hide_head' => true,
];
