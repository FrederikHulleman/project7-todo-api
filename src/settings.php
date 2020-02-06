<?php
return [
    'settings' => [
        'displayErrorDetails' => true,

        //API
        'api' => [
            'version' => 'v1',
            'base_url' => 'http://localhost',
        ],

        //db settings for eloquent ORM
        'db' => [
            'driver' => 'sqlite',
            'host' => 'localhost',
            'database' => __DIR__ . '/todo.db',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'foreign_key_constraints' => true,
        ],
    ],
];
