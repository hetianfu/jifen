<?php
return [

//队列任务
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/province-job'],
        'extraPatterns' => [
            'GET push' => 'push',
            'GET created' => 'created',
        ]
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/common'],
        'extraPatterns' => [
            'GET get-currency-time' => 'get-currency-time',
            'GET get-roll-buy-record' => 'get-roll-buy-record',

        ]
    ],

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/wx-image'],
        'extraPatterns' => [
            'POST user-share-img' => 'user-share-img',
        ]
    ],
     [
         'class' => 'yii\rest\UrlRule',
         'controller' => ['mobile/app-version'],
         'extraPatterns' => [
             'GET get-last-version' => 'get-last-version',
         ]
     ],
    //系统配置
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/system-group'],
        'extraPatterns' => [
            'GET get-index-data' => 'get-index-data',
            'GET get-index-detail-by-id/<id:\S+>' => 'get-index-detail-by-id',

            'GET get-index-detail-page' => 'get-index-detail-page',
            'GET get-index-app-config' => 'get-index-app-config',
            'GET get-system-group-data' => 'get-system-group-data',
            'GET get-list' => 'get-list',
            'GET get-one-by-id/<id:\S+>' => 'get-one-by-id',

            'GET get-common-config' => 'get-common-config',
            'GET get-common-son-config' => 'get-common-son-config',
            'GET get-common-son-config-list' => 'get-common-son-config-list',
        ]
    ],  //页面装修
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/pag-config'],
        'extraPatterns' => [
            'GET get-by-title/<id:\S+>' => 'get-by-title',
            'GET get-page' => 'get-page',
        ]
    ], //拼团
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/pink'],
        'extraPatterns' => [
            'POST add' => 'add',
            'GET get-partake-list' => 'get-partake-list',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'GET get-by-order-id/<id:\S+>' => 'get-by-order-id',
            'GET get-by-product-id' => 'get-by-product-id',
            'GET get-pink-product-ids' => 'get-pink-product-ids',
            'GET get-pink-product-page' => 'get-pink-product-page',
            'GET get-pink-page' => 'get-pink-page',
        ]
    ],
    //文章咨讯
    [   'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/information'],
        'extraPatterns' => [
            'GET get-category-list' => 'get-category-list',
            'GET get-category-by-id/<id:\S+>' => 'get-category-by-id',


            'GET get-information-by-id/<id:\S+>' => 'get-information-by-id',
            'GET get-information-page' => 'get-information-page',


        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/wx-pay'],
        'extraPatterns' => [

            'GET set-sub-merchant' => 'set-sub-merchant',
            'GET get-js-sdk' => 'get-js-sdk',

            'GET get-pay-config' => 'get-pay-config',
            'GET create-pay-code' => 'create-pay-qr-code',

            'GET unify-pay-order' => 'unify-pay-order',


        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/wx-message'],
        'extraPatterns' => [
            'GET get-msg-scene' => 'get-msg-scene',
            'GET send-pay-msg' => 'send-pay-msg',
        ]
    ],

    //会员基础信息
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-login'],
        'extraPatterns' => [

            'GET login-mini-program' => 'login-mini-program',
            'GET code-to-session' => 'code-to-session',
            'PATCH update-user-info-by-id' => 'update-user-info-by-id',
            'GET decrypt-data' => 'decrypt-data',
        ]
    ],
    //会员中心
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user'],
        'extraPatterns' => [
            //基础信息
            'POST register-user-info' => 'register-user-info',
            'GET get-mini-app-code' => 'get-mini-app-code',

            'GET get-info' => 'get-info',
            'GET get-disciple-page' => 'get-disciple-page',

            'PUT update-info-by-id' => 'update-info-by-id',
            //同步微信头像
            'PUT async-wechat-info' => 'async-wechat-info',

            //我的收藏
            'POST add-favorites' => 'add-favorites',
            'GET get-favorites-page' => 'get-favorites-page',
            'DELETE del-favorites-by-id/<id:\S+>' => 'del-favorites-by-id',
            //购物车
            'POST add-shop-cart' => 'add-shop-cart',
            'GET get-shop-cart-page' => 'get-shop-cart-page',
            'GET get-shop-cart-by-id/<id:\S+>' => 'get-shop-cart-by-id',
            'PUT update-shop-cart-number' => 'update-shop-cart-number',

            'PUT update-shop-cart-by-id' => 'update-shop-cart-by-id',

            'DELETE del-shop-cart-by-id/<id:\S+>' => 'del-shop-cart-by-id',
            'DELETE del-shop-cart' => 'del-shop-cart',
            //用户优惠券
            'POST add-coupon' => 'add-coupon',
            'DELETE del-coupon-by-id/<id:\S+>' => 'del-coupon-by-id',
            'GET get-coupon-page' => 'get-coupon-page',
            'GET get-my-coupon-page' => 'get-my-coupon-page',
            //收货地址管理
            'POST add-address' => 'add-address',
            'DELETE del-address-by-id/<id:\S+>' => 'del-address-by-id',
            'GET get-address-page' => 'get-address-page',
            'GET get-address-list' => 'get-address-list',
            'GET get-address-by-id/<id:\S+>' => 'get-address-by-id',
            'PUT update-address-by-id' => 'update-address-by-id',
            'PUT set-default-address' => 'set-default-address',
            //用户流水
            'GET get-wallet-detail-page' => 'get-wallet-detail-page',
            'GET get-wallet-detail-by-id/<id:\S+>' => 'get-wallet-detail-by-id',
            //提现
            'POST apply-draw-to-wx' => 'apply-draw-to-wx',
            'POST apply-draw-to-wallet' => 'apply-draw-to-wallet',
            //佣金列表
            'GET get-commission-page' => 'get-commission-page',
            //VIP充值终身会员
            'GET permanent-vip-pay' => 'permanent-vip-pay',
            'GET count-vip-pay' => 'count-vip-pay',

        ]
    ],
    //用户佣金
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-commission'],
        'extraPatterns' => [
            'GET get' => 'get',
            'GET come-in-this-month' => 'come-in-this-month',
            'GET get-detail-page' => 'get-detail-page',
            'GET get-draw-detail-page' => 'get-draw-detail-page',
            'GET get-disciple-detail-page' => 'get-disciple-detail-page',

        ]
    ],
    //用户积分
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-score'],
        'extraPatterns' => [
            'GET get-sign-score' => 'get-sign-score',
            'GET get-score-config' => 'get-score-config',
            'POST sign-score' => 'sign-score',
            'GET get-detail-page' => 'get-detail-page',
            'GET user-score' => 'user-score',
        ]
    ],
    //用户充值
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-charge'],
        'extraPatterns' => [
            //钱包充值
            'POST charge' => 'charge',
            'POST wx-pay-notify' => 'wx-pay-notify',
            //购买VIP
            'POST buy-vip' => 'buy-vip',
            'POST vip-pay-notify' => 'vip-pay-notify',

            'GET test-job' => 'test-job',
        ]
    ],
    //商户信息
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/merchant'],
        'extraPatterns' => [
           // 'GET get-score-config' => 'get-score-config',
            'GET get-shop-list' => 'get-shop-list',
            'GET get-shop-page' => 'get-shop-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
        ]
    ],


//    //会员基础信息
//    [
//        'class' => 'yii\rest\UrlRule',
//        'controller' => ['mobile/user-info'],
//        'extraPatterns' => [
//
//            'GET get-user-info-id/<id:\S+>' => 'get-user-info-id',
//            'GET get-user-info-page' => 'get-user-info-page',
//            'PATCH update-user-info-by-id' => 'update-user-info-by-id',
//
//        ]
//    ],
//    //用户模块
//    [
//        'class' => 'yii\rest\UrlRule',
//        'controller' => ['mobile/user-shop-cart'],
//        'extraPatterns' => [
//
//            //购物车
//            'POST add-user-shop-cart' => 'add-user-shop-cart',
//            'GET get-user-shop-cart-page' => 'get-user-shop-cart-page',
//            'PATCH update-user-shop-cart-number' => 'update-user-shop-cart-number',
//            'DELETE del-user-shop-cart-by-id/<id:\S+>' => 'del-user-shop-cart-by-id',
//
//        ]
//    ],

    //用户优惠券
//    [
//        'class' => 'yii\rest\UrlRule',
//        'controller' => ['mobile/user-coupon'],
//        'extraPatterns' => [
//            'POST add-coupon' => 'add-coupon',
//            'GET get-user-level-id/<id:\S+>' => 'get-user-info-id',
//            'GET get-user-level-page' => 'get-user-level-page',
//            'GET get-user-level-list' => 'get-user-level-list',
//            'PATCH update-user-level-by-id' => 'update-user-level-by-id',
//            'DELETE delete-user-level-by-id/<id:\S+>' => 'delete-user-level-by-id',
//
//        ]
//    ],
    //优惠券
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/coupon'],
        'extraPatterns' => [
            'GET get-coupon-page' => 'get-coupon-page',
        ]
    ],
    //我的收藏
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-favorite'],
        'extraPatterns' => [
            'POST add' => 'add',
            'GET get-page' => 'get-page',
            //'PATCH update-by-id' => 'update-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',
        ]
    ],
    //分类
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/category'],
        'extraPatterns' => [
            'GET get-list' => 'get-list',
        ]
    ],
    //商品
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/sale-strategy'],
        'extraPatterns' => [
            'GET get-list' => 'get-list',
            'GET get-hours' => 'get-hours',
            'GET get-product' => 'get-product',
        ]
    ],


    //商品
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/product'],
        'extraPatterns' => [
            'GET get-page' => 'get-page',

            'GET get-by-id/<id:\S+>' => 'get-by-id',

            'GET get-sku-by-id/<id:\S+>' => 'get-sku-by-id',
            'POST create-share-img' =>'create-share-img',

            'POST thumb-product' => 'thumb-product',
            'POST cancel-thumb-product' => 'cancel-thumb-product',
        ]
    ],
    //商品评论
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/product-reply'],
        'extraPatterns' => [

            'POST add' => 'add',
            'GET get-page' => 'get-page',
            'GET get-recommend-page' => 'get-recommend-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'GET get-by-order-id/<id:\S+>' => 'get-by-order-id',
            'DELETE del-by-id/<id:\S+>' =>'del-by-id',
        ]
    ],
    //上传评论图片
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/upload-img'],
        'extraPatterns' => [

            'POST upload-reply' => 'upload-reply',
        ]
    ],
    //订单详情
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/order'],
        'extraPatterns' => [

            'GET get-connect-user' => 'get-connect-user',
            'GET get-freight-address' => 'get-freight-address',

            'GET get-page' => 'get-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',

            'GET get-qr-code/<id:\S+>' => 'get-qr-code',
            'PUT receive-by-id' => 'receive-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',
            'PUT cancel-by-id/<id:\S+>' => 'cancel-by-id',

            'POST refund-by-id' => 'refund-by-id',
        ]
    ],
    //积分支付
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/order-score'],
        'extraPatterns' => [
            'POST calculate-order' => 'calculate-order',

            'POST submit-order' => 'submit-order',
            'GET go-to-pay/<id:\S+>' => 'go-to-pay',

        ]
    ],
    //订单支付
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/order-pay'],
        'extraPatterns' => [

            'GET direct-pay' => 'direct-pay',
            'POST calculate-order' => 'calculate-order',
            'POST get-effect-coupon' => 'get-effect-coupon',

            'POST submit-order' => 'submit-order',
            'GET go-to-pay/<id:\S+>' => 'go-to-pay',
            'GET go-to-pay-in-mp/<id:\S+>' => 'go-to-pay-in-mp',

            'GET h5-to-pay/<id:\S+>' => 'h5-to-pay',

            'POST wallet-submit-order' => 'wallet-submit-order',
            'GET wallet-to-pay/<id:\S+>' => 'wallet-to-pay',
            'GET after-to-pay/<id:\S+>' => 'after-to-pay',

            'POST wx-pay-notify' => 'wx-pay-notify',


            'GET test-job' => 'test-job',
        ]
    ],
    //支付宝网页付款
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/ali-pay'],
        'extraPatterns' => [
            'GET h5-to-pay/<id:\S+>' => 'h5-to-pay',
           'POST ali-pay-notify' => 'ali-pay-notify',
            'GET test-job' => 'test-job',
        ]
    ],
    //用户分享
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/user-share'],
        'extraPatterns' => [

            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'GET get-share-code' => 'get-share-code',

            'GET get-product-share-code' => 'get-product-share-code',

            'GET get-one-by-type' => 'get-one-by-type',
        ]
    ],
    //优惠券
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/coupon'],
        'extraPatterns' => [

            'GET get-coupon-page' => 'get-coupon-page',
        ]
    ],
    //  定时任务
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['mobile/task'],
        'extraPatterns' => [
            'POST cancel-order' => 'cancel-order',
            'POST seven-days-closed' => 'seven-days-closed',

            'POST disable-coupon' => 'disable-coupon',
            'POST disable-user-coupon' => 'disable-user-coupon',

            'POST test' => 'test',
        ]
    ],

    //短信注册
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['mobile/sms'],
        'extraPatterns' => [

            'POST send-reg-msg' => 'send-reg-msg',
            'POST msg-reg' => 'msg-reg',
            'GET refresh' => 'refresh',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['mobile/channel'],
        'extraPatterns' => [

            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'POST web-log-in' => 'web-log-in',
        ]
    ],
    //团购
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/assemble'],
        'extraPatterns' => [

            'POST add-assemble-config' => 'add-assemble-config',
            'PATCH update-assemble-config-by-id' => 'update-assemble-config-by-id',
            'GET get-assemble-config-by-id/<id:\S+>' => 'get-assemble-config-by-id',
            'GET get-assemble-config-page' => 'get-assemble-config-page',
            'DELETE del-assemble-config-by-id/<id:\S+>' => 'del-assemble-config-by-id',


            'POST add-product-assemble' => 'add-product-assemble',
            'PATCH update-product-assemble-by-id' => 'update-product-assemble-by-id',
            'GET get-product-assemble-by-id/<id:\S+>' => 'get-product-assemble-by-id',
            'GET get-product-assemble-page' => 'get-product-assemble-page',
            'DELETE del-product-assemble-by-id/<id:\S+>' => 'del-product-assemble-by-id',

        ]
    ],
    //秒杀
//    [
//        'class' => 'yii\rest\UrlRule',
//        'pluralize' => false,
//        'controller' => ['seller/product-kill'],
//        'extraPatterns' => [
//
//            'POST add-product-kill' => 'add-product-kill',
//            'PATCH update-product-kill-by-id' => 'update-product-kill-by-id',
//            'GET get-product-kill-by-id/<id:\S+>' => 'get-product-kill-by-id',
//            'GET get-product-kill-page' => 'get-product-kill-page',
//            'DELETE del-product-kill-by-id/<id:\S+>' => 'del-product-kill-by-id',
//
//        ]
//    ],

    //用户核销卡券
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/user-coupon'],
        'extraPatterns' => [

            'DELETE del-user-coupon/<id:\S+>' => 'del-user-coupon',
            //后台核销数量
            'PATCH update-user-coupon' => 'update-user-coupon',
            'PATCH change-user-coupon' => 'change-user-coupon',
            'GET get-user-coupon-page' => 'get-user-coupon-page',
            'GET get-user-coupon-detail-by-id/<id:\S+>' => 'get-user-coupon-detail-by-id',

            'GET get-user-coupon-detail-page' => 'get-user-coupon-detail-page',
            'GET get-check-order-record-page' => 'get-check-order-record-page',

            'DELETE del-user-coupon-detail/<id:\S+>' => 'del-user-coupon-detail',
            'PATCH update-user-coupon-detail' => 'update-user-coupon-detail',

        ]
    ],

//店铺微信认证
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/shop-register'],
        'extraPatterns' => [

            'POST add-shop-register' => 'add-shop-register',

            'PATCH submit-shop-register' => 'submit-shop-register',

            'GET get-shop-register-by-id/<id:\S+>' => 'get-shop-register-by-id',

        ]
    ],
    //分析页
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/shop-statistic'],
        'extraPatterns' => [

            'GET get-statistic-page' => 'get-statistic-page',

        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['mobile/user-mp'],
        'extraPatterns' => [
            'POST login' => 'login',
            'GET get-auth-info' => 'get-auth-info',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['manage/wechat'],
        'extraPatterns' => [

            'GET oauth' => 'oauth',
            'GET oauth-redirect' => 'oauth-redirect',
            'GET code-to-user' => 'code-to-user',
            'GET get-js-sdk' => 'get-js-sdk',
        ]
    ],
    //投诉管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['mobile/complain'],
        'extraPatterns' => [
            'POST add' => 'add',
//            'GET get-page' => 'get-page',
//            'DELETE del-by-id' => 'del-by-id',
//            'PATCH update-by-id' => 'update-by-id',
//            'GET get-by-id/<id:\S+>' => 'get-by-id',
        ]
    ],
];