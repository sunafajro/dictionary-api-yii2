<?php

$params = require(__DIR__ . '/params-console.php');

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
    ],
    'aliases' => [
        '@config' => dirname(__DIR__) . '/config',
        '@data'   => dirname(__DIR__) . '/data',
    ],
    'params' => $params,
];