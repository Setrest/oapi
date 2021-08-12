<?php

return [

    'title' => 'Nutrioniq API documentation',
    'description' => 'Description',
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
];
