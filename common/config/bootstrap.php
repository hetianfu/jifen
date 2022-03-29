<?php
Yii::setAlias('@common', dirname(__DIR__));

Yii::setAlias('@fanyou', dirname(dirname(__DIR__)) . '/fanyou');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@services', dirname(dirname(__DIR__)) . '/services');
Yii::setAlias('@storage', dirname(dirname(__DIR__)) . '/storage');
Yii::setAlias('@oauth2', dirname(dirname(__DIR__)) . '/oauth2');
Yii::setAlias('@merchant', dirname(dirname(__DIR__)) . '/merchant');
Yii::setAlias('@root', dirname(dirname(__DIR__)) . '/');

// 各自应用域名配置，如果没有配置应用独立域名请忽略
Yii::setAlias('@attachment', dirname(dirname(__DIR__)) . '/web/attachment'); // 本地资源目录绝对路径
Yii::setAlias('@attachurl', '/attachment'); // 资源目前相对路径，可以带独立域名


Yii::setAlias('@alifont', dirname(dirname(__DIR__)) . '/api/web/assets/fonts/alifont'); // 本地资源目录绝对路径


Yii::setAlias('@storageUrl', '');
Yii::setAlias('@oauth2Url', 'api/manage/wechats/oauth');
Yii::setAlias('@oauth2RedirectUrl', 'api/manage/wechats/oauth-redirect');