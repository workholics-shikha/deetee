<?php

$config = require_once 'config.php';
$account = require_once 'account.php';
$array = array_merge($account['paths']);
$definitions = array_merge($account['definitions']);
$json = [
    'swagger' => '1.0',
    'info' => [
        'version' => '1.0.0',
        'title' => 'Tablet: Operator APIs',
    ],
    'host' => $config['baseUrl'],
    'basePath' => '/api',
    'schemes' => [
        'http', 'https',
    ],
    'tags' => [

        [
            'name' => 'account',
            'description' => 'All Account api',
        ],

    ],
    'paths' => $array,
    'definitions' => $definitions,
];
echo json_encode($json);
