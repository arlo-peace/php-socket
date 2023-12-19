<?php
return [
    'database' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'jk_db',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ],
    'log' => [
        'path' => dirname(__DIR__) . '/storages/logs/',
        'sub' => 'month', // date|month|''
        'type' => 'day' // file|day|date
    ]
];