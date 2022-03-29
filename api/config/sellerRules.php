<?php
return [
    //permission
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/perssion'],
        'extraPatterns' => [
            'GET add' => 'add',
            'GET test' => 'test',
        ]
    ],
    //基类
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/basic'],
        'extraPatterns' => [

            'GET get-query-day' => 'get-query-day',
        ]
    ],
    //  定时任务
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/task'],
        'extraPatterns' => [
            'POST refresh-config' => 'refresh-config',
            'GET get-task-page' => 'get-task-page',
            'PATCH update-task-by-id' => 'update-task-by-id',
            'POST closed-pink' => 'closed-pink',

        ]
    ],

    //微信服务事件
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/wx-service'],
        'extraPatterns' => [

            'GET push' => 'bind',
            'POST push' => 'push',
        ]
    ], //拼团
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/pink'],
        'extraPatterns' => [

            'POST add-config' => 'add-config',
            'PATCH update-config-by-id' => 'update-config-by-id',
            'GET get-config-page' => 'get-config-page',
            'DELETE del-config-by-id/<id:\S+>' => 'del-config-by-id',

            'PATCH update-pink-by-id' => 'update-pink-by-id',
            'GET get-pink-page' => 'get-pink-page',
            'POST finish-pink' => 'finish-pink',

            'GET get-pink-partake-list' => 'get-partake-list',

        ]
    ],

    //后台登陆
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/login'],
        'extraPatterns' => [

            'GET is-init' => 'is-init',
            'GET basic-config' => 'basic-config',

            'POST get-auth-key' => 'get-auth-key',
            'GET get-auth-info' => 'get-auth-info',
        ]
    ],
    //会员
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/user-info'],
        'extraPatterns' => [
            'PATCH update-black' => 'update-black',
            'PATCH update-white' => 'update-white',

            'GET get-user-info-id/<id:\S+>' => 'get-user-info-id',
            'GET get-user-info-page' => 'get-user-info-page',
            'GET search-user-info-page' => 'search-user-info-page',
            'PATCH update-user-info-by-id' => 'update-user-info-by-id',


            'GET get-user-level-id/<id:\S+>' => 'get-user-info-id',
            'GET get-user-level-page' => 'get-user-level-page',
            'GET get-user-level-list' => 'get-user-level-list',
            'PATCH update-user-level-by-id' => 'update-user-level-by-id',
            'DELETE del-user-level-by-id/<id:\S+>' => 'del-user-level-by-id',



            'POST charge-user-wallet' => 'charge-user-wallet',
            'POST deduct-user-wallet' => 'deduct-user-wallet',

            'GET statistic-user-score' => 'statistic-user-score',
            'GET get-score-detail-by-id/<id:\S+>' => 'get-score-detail-by-id',
            'GET get-score-detail-page' => 'get-score-detail-page',
            'POST charge-user-score' => 'charge-user-score',
            'POST deduct-user-score' => 'deduct-user-score',

            'GET export' => 'export',


        ]
    ],
    //用户地址
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/user-address'],
        'extraPatterns' => [
            'GET get-page' => 'get-page',
        ]
    ],
    //用户提现申请
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/user-draw-apply'],
        'extraPatterns' => [

            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'GET get-page' => 'get-page',
            'GET sum' => 'sum',
            'PATCH approve-by-id' => 'approve-by-id',
            'PATCH forbid-by-id' => 'forbid-by-id',
        ]
    ],
    //商户
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/merchant'],
        'extraPatterns' => [
            'GET get-merchant-info' => 'get-merchant-info',
            'PATCH update-merchant-info' => 'update-merchant-info',
        ]
    ],
    //门店
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/shop'],
        'extraPatterns' => [
            'POST add-shop' => 'add-shop',
            'GET get-shop-by-id/<id:\S+>' => 'get-shop-by-id',
            'GET get-shop-page' => 'get-shop-page',
            'PATCH update-shop-by-id' => 'update-shop-by-id',
            'DELETE delete-shop-by-id/<id:\S+>' => 'del-shop-by-id',
        ]
    ],
    //角色
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/auth-role'],
        'extraPatterns' => [
            'POST add-auth-role' => 'add-auth-role',
            'POST assign-auth-role'=>'assign-auth-role',
            'GET get-auth-role-by-id/<id:\S+>' => 'get-auth-role-by-id',
            'GET get-auth-role-page' => 'get-auth-role-page',
            'GET get-auth-role-list' => 'get-auth-role-list',
            'PATCH update-auth-role' => 'update-auth-role',
            'DELETE delete-auth-role/<id:\S+>' => 'del-auth-role',
        ]
    ],
    //权限
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/auth-item'],
        'extraPatterns' => [
            'POST add-auth-item' => 'add-auth-item',
            'GET get-auth-item-page' => 'get-auth-item-page',
            'GET get-auth-item-list' => 'get-auth-item-list',
            'GET get-all' => 'get-all',

            'GET get-auth-item-by-roles' => 'get-auth-item-by-roles',
            'GET get-currency-menu-item' => 'get-currency-menu-item',

            'PATCH update-auth-item' => 'update-auth-item',
            'DELETE delete-auth-item/<id:\S+>' => 'del-auth-item',
        ]
    ],
    //用户角色权限
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/common-auth-assignment'],
        'extraPatterns' => [
            'POST add-common-auth-assignment' => 'add-common-auth-assignment',
            'GET get-common-auth-assignment-page' => 'get-common-auth-assignment-page',
            'GET get-common-auth-assignment-list' => 'get-common-auth-assignment-list',
            'PATCH update-common-auth-assignment' => 'update-common-auth-assignment',
        ]
    ],
    //上传图片接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/upload-img'],
        'extraPatterns' => [
            //分类上传目录
            'POST add-category-image' => 'add-category-image',
            //商品上传目录
            'POST add-product-image' => 'add-product-image',
            'POST add-product-video' => 'add-product-video',
            //上传系统配置文件
            'POST add-system-image' => 'add-system-image',
            //上传资讯图片
            'POST add-information-image' => 'add-information-image',

            //商品富文本上传目录
            'POST add-product-rich-text-image' => 'add-product-rich-text-image',

            //店铺上传目录
            'POST add-shop-image' => 'add-shop-image',
            //区域上传目录
            'POST add-area-image' => 'add-area-image',

        ]
    ], //上传文件
    [
    'class' => 'yii\rest\UrlRule',
    'controller' => ['seller/upload-file'],
    'extraPatterns' => [
        //微信支付证书
        'POST upload-wx-pay-cert' => 'upload-wx-pay-cert',

        'POST upload-static-img' => 'upload-static-img',
    ]
],    //页面装修
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/page-config'],
        'extraPatterns' => [
            'POST add' => 'add-page-config',
            'PATCH update-by-id' => 'update-page-config-by-id',
            'GET get-by-id/<id:\S+>' => 'get-page-config-by-id',
            'GET get-page' => 'get-page-config-page',
            'DELETE del-by-id/<id:\S+>' => 'del-page-config-by-id',
        ]
    ],
    //运费模版
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/freight-template'],
        'extraPatterns' => [
            'POST add-freight-template' => 'add-freight-template',

            'PATCH update-freight-template-by-id' => 'update-freight-template-by-id',
            'GET get-freight-template-page' => 'get-freight-template-page',
            'GET get-freight-template-list' => 'get-freight-template-list',
            'GET get-freight-template-by-id/<id:\S+>' => 'get-freight-template-by-id',

            'DELETE del-freight-template-by-id/<id:\S+>' => 'del-freight-template-by-id',

        ]
    ],
    //分类接口
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/category'],
        'extraPatterns' => [
            'GET get-recursion-category-id-by-id/<id:\S+>' => 'get-recursion-category-id-by-id',

            'PATCH update-category' => 'update-category',
            'GET get-category-page' => 'get-category-page',
            'GET get-category-list' => 'get-category-list',
            'GET get-category-by-id/<id:\S+>' => 'get-category-by-id',

            'POST add-category' => 'add-category',
            'DELETE del-category/<id:\S+>' => 'del-category',

            'GET clean-category-redis' => 'clean-category-redis',
        ]
    ], //商品规格
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/product-spec'],
        'extraPatterns' => [
            'POST add-spec' => 'add-spec',
            'DELETE del-spec-by-id/<id:\S+>' => 'del-spec-by-id',
            'PATCH update-spec-by-id' => 'update-spec-by-id',

            'GET get-spec-by-id/<id:\S+>' => 'get-spec-by-id',
            'GET get-spec-page' => 'get-spec-page',
            'GET get-spec-list' => 'get-spec-list',

        ]
    ],
   //单位接口 //商品单位
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/unit'],
        'extraPatterns' => [
            'GET get-unit-page' => 'get-unit-page',
            'GET get-unit-list' => 'get-unit-list',
            'GET get-unit-by-id/<id:\S+>' => 'get-unit-by-id',

            'PATCH update-unit' => 'update-unit',
            'POST add-unit' => 'add-unit',
            'DELETE del-unit/<id:\S+>' => 'del-unit',

            'GET clean-unit-redis' => 'clean-unit-redis',
        ]
    ],  //商品
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/product'],
        'extraPatterns' => [

            'POST add-product' => 'add-product',

            'POST batch-add-product' => 'batch-add-product',

            'DELETE del-product-by-id/<id:\S+>' => 'del-product-by-id',
            'PATCH update-by-id' => 'update-by-id',
            'PATCH update-part-by-id' => 'update-part-by-id',
            'PATCH update-product-combo' => 'update-product-combo',

            'GET get-product-by-id/<id:\S+>' => 'get-product-by-id',
            'GET get-count' => 'get-count',
            'GET get-product-page' => 'get-product-page',
            'GET get-product-list' => 'get-product-list',

            'GET clean-share-img' => 'clean-share-img',

            //线上点单
            'POST add-online-product' => 'add-online-product',
            'PATCH update-online-product' => 'update-online-product',


            'PATCH submit-online-product' => 'submit-online-product',
            'DELETE del-online-product/<id:\S+>' => 'del-online-product',
            'GET get-online-product-by-id/<id:\S+>' => 'get-online-product-by-id',
            'GET get-online-product-page' => 'get-online-product-page',
            'GET get-mini-online-product-list' => 'get-mini-online-product-list',
            //添加商品sku
            'POST add-product-sku/<id:\S+>' => 'add-product-sku',
            'DELETE del-product-sku-by-id/<id:\S+>' => 'del-product-sku-by-id',
            'PATCH update-product-sku-by-id' => 'update-product-sku-by-id',
            'GET get-product-sku-list' => 'get-product-sku-list',
            //导出报表
            'GET export' => 'export',
            //导入快递单号
            'POST import-order-express-excel' => 'import-order-express-excel',

            'GET product-img-to-oss' => 'product-img-to-oss',
            'GET all-product-img-to-oss' => 'all-product-img-to-oss',

        ]
    ],  //商品评论
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/product-reply'],
        'extraPatterns' => [

            'GET get-page' => 'get-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'PATCH update-by-id' => 'update-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',

        ]
    ],  //商品
    //退单申请
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/refund-apply'],
        'extraPatterns' => [
            'GET get-page' => 'get-page',
            'GET sum' => 'sum',
            'GET get-one-by-id/<id:\S+>' => 'get-one-by-id',

            'GET test-refund-by-id/<id:\S+>' => 'test-refund-by-id',

            'PATCH approve/<id:\S+>' => 'approve',
            'PATCH forbid/<id:\S+>' => 'forbid',
        ]
    ],

    //在线点单设计
//    [
//        'class' => 'yii\rest\UrlRule',
//        'pluralize' => false,
//        'controller' => ['seller/online-order'],
//        'extraPatterns' => [
//            'POST add-online-order' => 'add-online-order',
//            'DELETE del-online-order/<id:\S+>' => 'del-online-order',
//            'PATCH update-online-order' => 'update-online-order',
//            'PATCH update-online-order-product' => 'update-online-order-product',
//
//            'GET get-online-order-by-id/<id:\S+>' => 'get-online-order-by-id',
//            'GET get-online-order-page' => 'get-category-online-page',
//            'GET get-online-order-list' => 'get-online-order-list',
//
//        ]
//    ],

    //商品
//    [
//        'class' => 'yii\rest\UrlRule',
//        'pluralize' => false,
//        'controller' => ['seller/mall-product'],
//        'extraPatterns' => [
//            //店铺取平台商品分布列表
//            'GET get-mall-product-page' => 'get-mall-product-page',
//            //店铺分页取平台商品日销售量
//            'GET get-product-day-report-page' => 'get-product-day-report-page',
//        ]
//    ],
 //打印机
    [
    'class' => 'yii\rest\UrlRule',
    'pluralize' => false,
    'controller' => ['seller/print'],
    'extraPatterns' => [
        'POST add-print' => 'add-print',

        'GET get-print-page' => 'get-print-page',
        'GET get-print-by-id/<id:\S+>' => 'get-print-by-id',

        'GET  print-test' => 'print-test',
        'PATCH update-print-by-id'=> 'update-print-by-id',

        'DELETE delete-print-by-id/<id:\S+>'=> 'delete-print-by-id',
    ]
    ],
    //库存
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/product-stock'],
        'extraPatterns' => [
            'POST enter-product-stock' => 'enter-product-stock',
            'POST out-product-stock' => 'out-product-stock',
            'GET get-product-list-for-stock' => 'get-product-list-for-stock',
//            'DELETE del-product-stock/<id:\S+>' => 'del-product-stock',
//            'PATCH update-product-stock' => 'update-product-stock',
            'GET get-product-stock-by-id/<id:\S+>' => 'get-product-stock-by-id',
            'GET get-product-stock-page' => 'get-product-stock-page',
            'GET get-product-stock-list' => 'get-product-stock-list',

            'GET get-product-stock-detail-page' => 'get-product-stock-detail-page',
            'GET get-product-stock-detail-list' => 'get-product-stock-detail-list',
            'GET get-product-stock-detail-by-id/<id:\S+>' => 'get-product-stock-detail-by-id',

            'GET get-product-stock-rank' => 'get-product-stock-rank',

//            'GET get-product-stock-history-page' => 'get-product-stock-history-page',
//            'GET get-product-stock-history-by-id/<id:\S+>' => 'get-product-stock-history-by-id',

            //查询店铺库存状态
            'GET get-product-stock-lock-status' => 'get-product-stock-lock',
//            'PATCH lock-product-stock' => 'lock-product-stock',
//            'PATCH unlock-product-stock' => 'un-lock-product-stock',
//
//            'POST inventory-product-stock' => 'inventory-product-stock',
//            'GET get-inventory-detail-page' => 'get-inventory-detail-page',

            //清除库存缓存
//            'GET clean-stock-redis' => 'clean-stock-redis',
        ]
    ],

    //订单
        [
            'class' => 'yii\rest\UrlRule',
            'pluralize' => false,
            'controller' => ['seller/order-info'],
            'extraPatterns' => [
                'POST add-order-info' => 'add-order-info',
                'DELETE del-order-info/<id:\S+>' => 'del-order-info',
                'PATCH update-by-id' => 'update-by-id',
                'GET get-order-info-by-id/<id:\S+>' => 'get-order-info-by-id',
                'GET get-order-info-page' => 'get-order-info-page',
                'GET get-order-info-list' => 'get-order-info-list',
                'GET count-order-info-title' => 'count-order-info-title',
                'GET sum-order-info-title' => 'sum-order-info-title',
                //打印订单
                'POST  print-order' => 'print-order',
                //核销订单
                'POST  check-order' => 'check-order',

                //查询订单日报表
                'GET get-order-day-report-page' => 'get-order-day-report-page',
                //获得取获商品列表
                'GET get-claim-product-page' => 'get-claim-product-page',
                'GET get-claim-product-by-id/<id:\S+>' => 'get-claim-product-by-id',
                'PATCH update-claim-product-by-id' => 'update-claim-product-by-id',

                //商家接单，商家申请退单
                'PATCH send-order-info' => 'send-order-info',
                'PATCH complete-order-info'=>'complete-order-info',
                'PATCH refund-order' => 'refund-order',
                'POST do-reminder' => 'do-reminder',
                'POST send-sms-msg' => 'send-sms-msg',
                'GET export' => 'export',

                'GET query-kdi' => 'query-kdi',
            ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/split-order'],
        'extraPatterns' => [

            'PATCH batch-send-split-order' => 'batch-send-split-order',

            'PATCH update-split-order' => 'update-split-order',
            'GET get-split-order-by-id/<id:\S+>' => 'get-split-order-by-id',
            'GET get-split-order-page' => 'get-split-order-page',
            'GET sum-split-order' => 'sum-split-order',
        ]
    ],
    //优惠券
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/coupon'],
        'extraPatterns' => [
            'POST add-coupon-template' => 'add-coupon-template',
            'PATCH update-coupon-template-by-id' => 'update-coupon-template',
            'GET get-coupon-template-by-id/<id:\S+>' => 'get-coupon-template-by-id',
            'GET get-coupon-template-page' => 'get-coupon-template-page',
            'GET get-coupon-template-list' => 'get-coupon-template-list',
            'DELETE del-coupon-template-by-id/<id:\S+>' => 'del-coupon-template-by-id',

            'POST publish-coupon' => 'publish-coupon',
            'PATCH update-coupon-by-id' => 'update-coupon',
            'GET get-coupon-by-id/<id:\S+>' => 'get-coupon-by-id',
            'GET get-coupon-page' => 'get-coupon-page',
            'DELETE del-coupon-by-id/<id:\S+>' => 'del-coupon-by-id',
        ]
    ],
    //优惠券领取记录
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/coupon-user'],
        'extraPatterns' => [

            'POST send-to-user' => 'send-to-user',
            'PATCH update-coupon-user-by-id' => 'update-coupon-user',
            'GET get-coupon-user-by-id/<id:\S+>' => 'get-coupon-user-by-id',
            'GET get-coupon-user-page' => 'get-coupon-user-page',

        ]
    ],  //分销关系
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/distribute-user'],
        'extraPatterns' => [

            'POST add' => 'add',
            'PATCH update-by-id' => 'update-by-id',
            'GET get-id/<id:\S+>' => 'get-by-id',
            'GET get-page' => 'get-page',
            'GET get-partner-page' => 'get-partner-page',
            'DELETE  del-by-id/<id:\S+>' => 'del-by-id',

        ]
    ],


    //商品促销管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/sale-strategy'],
        'extraPatterns' => [

            'GET test-sale-product'=>'test-sale-product',

            'POST add-sale-strategy' => 'add-sale-strategy',
            'PATCH update-sale-strategy-by-id' => 'update-sale-strategy-by-id',
            'GET get-sale-strategy-by-id/<id:\S+>' => 'get-sale-strategy-by-id',
            'GET get-sale-strategy-page' => 'get-sale-strategy-page',
            'DELETE del-sale-strategy-by-id/<id:\S+>' => 'del-sale-strategy-by-id',

            'POST add-sale-product' => 'add-sale-product',
            'GET get-sale-product-page' => 'get-sale-product-page',
            'PATCH update-sale-product-by-id' => 'update-sale-product-by-id',
            'DELETE del-sale-product-by-id/<id:\S+>' => 'del-sale-product-by-id',

        ]
    ],
    //部门
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/shop-department'],
        'extraPatterns' => [
            'POST add-shop-department' => 'add-shop-department',
            'DELETE del-shop-department/<id:\S+>' => 'del-shop-department',
            'PATCH update-shop-department' => 'update-shop-department',
            'GET get-shop-department-by-id/<id:\S+>' => 'get-shop-department-by-id',
            'GET get-shop-department-page' => 'get-shop-department-page',
            'GET get-shop-department-list' => 'get-shop-department-list',
            //'GET get-shop-department-ids' => 'get-shop-department-ids',
        ]
    ],
    //店铺会员
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/shop-user'],
        'extraPatterns' => [
            'DELETE del-shop-user/<id:\S+>' => 'del-shop-user',
            'PATCH update-shop-user' => 'update-shop-user',
            'PATCH update-batch-white' => 'update-batch-white',
            'PATCH update-batch-black' => 'update-batch-black',
            'GET get-shop-user-by-id/<id:\S+>' => 'get-shop-user-by-id',
            'GET get-shop-user-page' => 'get-shop-user-page',
            'GET get-shop-user-list' => 'get-shop-user-list',

            'GET clean-user-redis' => 'clean-user-redis',
        ]
    ],
    //员工
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/shop-employee'],
        'extraPatterns' => [
            'POST add-shop-employee' => 'add-shop-employee',
            'POST bind-shop-department' => 'bind-shop-department',
            'DELETE del-shop-employee/<id:\S+>' => 'del-shop-employee',
            'PATCH update-shop-employee' => 'update-shop-employee',
            'PATCH update-password' => 'update-password',

            //清除店员与微信绑定关系
            'PATCH clean-shop-employee' => 'clean-shop-employee',


            'GET get-shop-employee-by-id/<id:\S+>' => 'get-shop-employee-by-id',
            'GET get-shop-employee-page' => 'get-shop-employee-page',
            'GET get-shop-employee-list' => 'get-shop-employee-list',
            //获取微信公众号二维码
            'GET get-mp-qr-code' => 'get-mp-qr-code',

            //员工绑定的消息推送类型
            'GET get-employee-msg-type-list' => 'get-employee-msg-type-list',
            'POST add-employee-msg-type' => 'add-employee-msg-type',
            'GET delete-employee-msg-type' => 'delete-employee-msg-type',


            'GET clean-employee-redis' => 'clean-employee-redis',
        ]
    ],
    //用户核销卡券
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/user-check-code'],
        'extraPatterns' => [

            'DELETE del-user-coupon/<id:\S+>' => 'del-user-coupon',
            //后台核销数量
            'PATCH update-user-coupon' => 'update-user-coupon',
            'PATCH change-user-coupon' => 'change-user-coupon',
            'GET get-user-coupon-page' => 'get-user-coupon-page',
            'GET get-user-coupon-detail-by-id/<id:\S+>' => 'get-user-coupon-detail-by-id',
            'GET get-sum' => 'get-sum',

            'GET get-user-coupon-detail-page' => 'get-user-coupon-detail-page',
            'GET get-check-order-record-page' => 'get-check-order-record-page',

            'DELETE del-user-coupon-detail/<id:\S+>' => 'del-user-coupon-detail',
            'PATCH update-user-coupon-detail' => 'update-user-coupon-detail',

        ]
    ],

    //供应商发货记录
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/supplier'],
        'extraPatterns' => [

          // 'PATCH send-order-send-record/<id:\S+>' => 'send-order-send-record',

            'PATCH update-order-send-record-by-id' => 'update-order-send-record-by-id',

            'GET get-order-send-record-page' => 'get-order-send-record-page',
            'GET get-order-receive-record-page' => 'get-order-receive-record-page',
            'GET get-order-send-record-by-id/<id:\S+>' => 'get-order-send-record-by-id',

            //小区购商家确认收货
            'PATCH reach-order' => 'reach-order',


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

    //文章咨讯
    [   'class' => 'yii\rest\UrlRule',
        'controller' => ['seller/information'],
        'extraPatterns' => [
            'POST add-category' => 'add-category',
            'PATCH update-category-by-id' => 'update-category-by-id',
            'GET get-category-list' => 'get-category-list',
            'GET get-category-by-id/<id:\S+>' => 'get-category-by-id',
            'DELETE del-category-by-id/<id:\S+>' => 'del-category-by-id',

            'POST add-information' => 'add-information',
            'PATCH update-information-by-id' => 'update-information-by-id',

            'GET get-information-by-id/<id:\S+>' => 'get-information-by-id',
            'GET get-information-page' => 'get-information-page',

            'PATCH publish-information-by-id' => 'publish-information-by-id',
            'DELETE del-information-by-id/<id:\S+>' => 'del-information-by-id',

        ]
    ],

    //分析页
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/statistic'],
        'extraPatterns' => [

            'GET get-statistic-title' => 'get-statistic-title',
            'GET get-statistic-page' => 'get-statistic-page',
            'GET get-statistic-rank' => 'get-statistic-rank',

        ]
    ],

    //财务--资金监控
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/system-finance'],
        'extraPatterns' => [

            'GET get-system-finance-by-id/<id:\S+>' => 'get-system-finance-by-id',
            'GET get-system-finance-page' => 'get-system-finance-page',

        ]
    ],


    //会员购买记录
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/user-vip'],
        'extraPatterns' => [

            'GET get-user-vip-pay-page' => 'get-user-vip-pay-page',
            'GET get-user-vip-detail-page' => 'get-vip-detail-page',

            'GET get-system-finance-page' => 'get-system-finance-page',
            'PATCH update-user-vip-pay-by-id' => 'update-user-vip-pay-by-id',


        ]
    ],
    //渠道管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/channel'],
        'extraPatterns' => [

            'POST add' => 'add',
            'GET get-page' => 'get-page',

            'GET get-list' => 'get-list',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',
            'PATCH update-by-id' => 'update-by-id',
        ]
    ],
    //短信管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/sms-template'],
        'extraPatterns' => [

            'POST add' => 'add',
            'GET get-page' => 'get-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'DELETE del-by-id/<id:\S+>' => 'del-by-id',
            'PATCH update-by-id' => 'update-by-id',

            'GET get-log-page' => 'get-log-page',
            'POST log-re-send' => 'log-re-send',

        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/api-for-99'],
        'extraPatterns' => [

            'GET get-detail' => 'get-detail',

        ]
    ],//第三方帐户
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/cloud-gather'],
        'extraPatterns' => [

            'GET get-account' => 'get-account',

            'GET get-detail-page' => 'get-detail-page',


        ]
    ],
    //对象管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/store-client'],
        'extraPatterns' => [

            'POST add' => 'add',
            'GET get-page' => 'get-page',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
            'DELETE del-by-id' => 'del-by-id',
            'PATCH update-by-id' => 'update-by-id',
            'PATCH move-to' => 'move-to',
            'POST add-category' => 'add-category',
            'GET get-category-page' => 'get-category-page',
            'GET get-category-list' => 'get-category-list',
            'GET get-category-by-id/<id:\S+>' => 'get-category-by-id',
            'DELETE del-category-by-id/<id:\S+>' => 'del-category-by-id',
            'PATCH update-category-by-id' => 'update-category-by-id',

        ]
    ],
    //对象管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/api-gather-product'],
        'extraPatterns' => [

            'GET gather-product' => 'gather-product'

        ]
    ],
    //投诉管理
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/complain'],
        'extraPatterns' => [
            'POST add' => 'add',
            'GET get-page' => 'get-page',
            'DELETE del-by-id' => 'del-by-id',
            'PATCH update-by-id' => 'update-by-id',
            'GET get-by-id/<id:\S+>' => 'get-by-id',
        ]
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => false,
        'controller' => ['seller/system-log'],
        'extraPatterns' => [

            'GET get-page' => 'get-page',

        ]
    ],

];