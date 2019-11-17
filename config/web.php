<?php

$params  = require(__DIR__ . '/params-web.php');
$aliases = require(__DIR__ . '/aliases.php');

$config = [
    'id' => 'app-web',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'controllerNamespace' => 'app\controllers',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '/api/book/<id:\d+>/file/<name:[\d\w\_\-]+>/<type:\w+>'  => '/book/api-file',
                '/api/book/<id:\d+>'                                     => '/book/api-view',
                '/api/books'                                             => '/book/api-index',
                '/api/term/<term:[\w\-]+>/<limit:\d+>/<offset:\d+>'      => '/term/api-search',
                '/api/term/<term:[\w\-]+>/<limit:\d+>'                   => '/term/api-search',
                '/api/term/<term:[\w\-]+>'                               => '/term/api-search',
                '/api/terms/<limit:\d+>/<offset:\d+>'                    => '/term/api-index',
                '/api/terms/<limit:\d+>'                                 => '/term/api-index',
                '/api/terms'                                             => '/term/api-index',
            ],
        ],
    ],
    'aliases' => $aliases,
    'params' => $params,
];

if (file_exists(__DIR__ . '/local/web.php')) {
    $config = array_merge($config, require(__DIR__ . '/local/web.php'));
}

return $config;