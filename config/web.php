<?php

$localPath = __DIR__ . '/local';

$params  = require(__DIR__ . '/params-web.php');

if (file_exists("{$localPath}/params-web.php")) {
    $localParams = require("{$localPath}/params-web.php");
    $params = array_merge($params, $localParams);
}

$aliases = require(__DIR__ . '/aliases.php');
if (file_exists("{$localPath}/aliases.php")) {
    $localAliases = require("{$localPath}/aliases.php");
    $aliases = array_merge($aliases, $localAliases);
}

$options = require(__DIR__ . '/options.php');
if (file_exists("{$localPath}/options.php")) {
    $localOptions = require("{$localPath}/options.php");
    $options = array_merge($options, $localOptions);
}

return [
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
            'enableCsrfValidation' => $options['enableCsrfValidation'],
            'cookieValidationKey'  => $options['cookieValidationKey'],
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => [
                '/api/books/<id:\d+>/file/<name:[\d\w\_\-]+>/<type:\w+>'            => '/book/api-file',
                '/api/books/<id:\d+>/<chapter:\d+>'                                 => '/book/api-chapter',
                '/api/books/<id:\d+>'                                               => '/book/api-book',
                '/api/books'                                                        => '/book/api-books',
                '/api/term/<term:[\w\-]+>/<limit:\d+>/<offset:\d+>'                 => '/term/api-search',
                '/api/term/<term:[\w\-]+>/<limit:\d+>'                              => '/term/api-search',
                '/api/term/<term:[\w\-]+>'                                          => '/term/api-search',
                '/api/terms/<limit:\d+>/<offset:\d+>'                               => '/term/api-index',
                '/api/terms/<limit:\d+>'                                            => '/term/api-index',
                '/api/terms'                                                        => '/term/api-index',
                '/api/dictionaries'                                                 => '/dictionary/list',
                '/api/dictionaries/<dictionary:\w+>'                                => '/dictionary/list-terms',
                '/api/dictionaries/<dictionary:\w+>/<term:[\w\-]+>'                 => '/dictionary/search-terms',
            ],
        ],
    ],
    'aliases' => $aliases,
    'params' => $params,
];
