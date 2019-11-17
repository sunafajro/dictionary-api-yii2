<?php

$params = require(__DIR__ . '/params-console.php');
$aliases = require(__DIR__ . '/aliases.php');

$config = [
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
    'aliases' => $aliases,
    'params' => $params,
];

if (file_exists(__DIR__ . '/local/console.php')) {
    $config = array_merge($config, require(__DIR__ . '/local/console.php'));
}

return $config;