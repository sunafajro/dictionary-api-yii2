<?php

$localPath = __DIR__ . '/local';

$params  = require(__DIR__ . '/params-console.php');
if (file_exists("{$localPath}/params-console.php")) {
    $localParams = require("{$localPath}/params-console.php");
    $params = array_merge($params, $localParams);
}

$aliases = require(__DIR__ . '/aliases.php');
if (file_exists("{$localPath}/aliases.php")) {
    $localAliases = require("{$localPath}/aliases.php");
    $aliases = array_merge($aliases, $localAliases);
}

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

return $config;