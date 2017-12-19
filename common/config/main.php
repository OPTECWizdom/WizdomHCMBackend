<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'controllerMap' => [
        'background-bus' =>'trntv\bus\console\BackgroundBusController',

    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
