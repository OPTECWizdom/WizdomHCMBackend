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
        'commandBus'=>[
            'class' => 'trntv\bus\CommandBus',
            'middlewares' => [

                [
                    'class' => 'common\components\trntv\bus\middleware\BackgroundCommandMiddlewareCustom',
                    'backgroundHandlerPath' => __DIR__."/../../yii",
                    'backgroundHandlerBinary' => 'php',
                    'backgroundHandlerRoute' => 'background-bus/handle',
                    //'backgroundHandlerArguments' => ['&'],
                    'backgroundProcessTimeout' => 1000000,
                    'backgroundProcessIdleTimeout' => 100000
                ]
            ]

        ],
        'workflowSource' => [
            'class' => 'raoul2000\workflow\source\file\WorkflowFileSource',
            'definitionLoader' => [
                'class' => 'raoul2000\workflow\source\file\PhpClassLoader',
                'namespace'  => 'backend\workflows'
            ]
        ],

    ],
];
