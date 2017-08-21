<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'workflowSource' => [
            'class' => 'raoul2000\workflow\source\file\WorkflowFileSource',
            'definitionLoader' => [
                'class' => 'raoul2000\workflow\source\file\PhpClassLoader',
                'namespace'  => '@app/workflows'
            ]
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
            'parsers' => ['application/json'=>'yii\web\JsonParser']
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                   'class'=>'yii\rest\UrlRule',
                   'controller' => 'FlujoProcesos',
                   'tokens' => [
                       '{id}'=>'<id:\\w+,*\\w+>'
                   ]

                ]
            ],
        ],

    ],
    'params' => $params,
];
