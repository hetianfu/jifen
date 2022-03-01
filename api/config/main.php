<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);
// 路由引入
$rules = array_merge(
    require __DIR__ . '/manageRules.php',
    require __DIR__ . '/mobileRules.php',
    require __DIR__ . '/sellerRules.php',
    require __DIR__ . '/sellerCommonRules.php'

);

return [
    'id' => '1',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'modules' => [
        'manage' => [
            'class' => 'api\modules\manage\Module'
        ] ,
        'mobile' => [
            'class' => 'api\modules\mobile\Module'
        ] ,
        'seller' => [
            'class' => 'api\modules\seller\Module'
        ] ,
        'seller_common' => [
            'class' => 'api\modules\seller\CommonModule'
        ] ,
    ],
    'bootstrap' => ['log'],
    'components' => [
        /** ------ 缓存 ------ **/
        'cache' => [
            /**
             * 文件缓存配置
             * 注意如果要改成非文件缓存请删除，否则会报错
             */
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@api/runtime/cache'
            /**
             * REDIS缓存  与文件缓存互斥
             * 启用时， 去掉下方注释，并且要注释 文件缓存 配置
             */
//            'class' => 'yii\redis\Cache',
//               'redis' => [
//                'class' => 'yii\redis\Connection',
//                'hostname' => '127.0.0.1',
//                'port' => 6379,
//                'database' => 0,
//                'password'  =>'123456',
//            ],
        ],
        'user' => [
           'class' => 'api\modules\auth\User',
            'enableSession' => false, //关闭session
            'loginUrl' => null, //登录跳转地址为空
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['error'],
                        'categories' => ['yii\db\*'],
                        'logFile' => '@runtime/logs/db/db-'.date('Ymd').'.log', //自定义文件路径
                        'maxFileSize' => 1024*2,//设置文件大小，以kB为单位
                        'maxLogFiles' => 30,//同名文件最大数量（实际数量+1）
                    ],
                ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                    if ($response->statusCode === 200) {
                        $response->data = [
                            'success' => $response->isSuccessful,
                            'code' => $response->getStatusCode(),
                            'message' => $response->statusText,
                            'data' =>is_null($response->data)?null:$response->data,
                        ];
                    } else if(isset($response->data['message'])) {
                        $response->data = [
                            'success' => $response->isSuccessful,
                            'code' => $response->getStatusCode(),
                            'message' => $response->data['message'],
                            'data' => null,
                        ];
                    }
            },
        ],
        'urlManager' => [
            // 美化Url,默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。启用容易出错
            // 注意:如果不需要严格解析路由请直接删除或注释此行代码
            'enableStrictParsing' => false,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            'showScriptName' => false,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。
            'rules' => $rules
        ],
    ],
    'params' => $params,
];