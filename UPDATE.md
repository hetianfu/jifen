# 更新日志

All notable changes to this project will be documented in this file.

The format is based on [Mini-App](https://fanyoukeji.net/service/mini_app.html)
## [1.3.8](https://fanyoukeji.net/) - 2021.8.27
### 新增货到付款


## [1.3.7.1](https://fanyoukeji.net/) - 2021.3.23
### 优化
- 添加访问缓存。
- 取消访问日志操作记录。
### 修复
- 新用户进入时，有一定机率出现积分空白页面。

## [1.3.7](https://fanyoukeji.net/) - 2021.3.18
### 优化
- 新增配置参数。

## [1.3.6.1](https://fanyoukeji.net/) - 2021.2.27
### 优化
- 优化渠道管理。
### 修复
- 修复在浏览器无痕模式下，同一手机号下的订单，刷新之后，找不到订单的问题。 

## [1.3.6](https://fanyoukeji.net/) - 2020.12.31
### 新增
- 增加公众号支付。

## [1.3.4](https://fanyoukeji.net/) - 2020.12.28
### 优化
- 重构按照商品搜索 订单的匹配规则。

## [1.3.3](https://fanyoukeji.net/) - 2020.12.09
### 修复
- 用户注册时，无渠道注册失败的问题。
## [1.3.2.2](https://fanyoukeji.net/) - 2020.12.07
### 修复
- 添加商品的时候，虚拟销量不生效的问题。
## [1.3.2.1](https://fanyoukeji.net/) - 2020.12.2
### 优化
   H5支付可以拉起多次。
   加长订单取消时间。
   - env文件添加订单支付超时时间
   - orderPayController 更新文件
   - sql rf_proxy_pay 取消唯一索引
## [1.3.2](https://fanyoukeji.net/) - 2020.11.09
### 新增
   初使化后台Logo配置功能。
### 修复
   更新用户分享图，存在缓存，更新失败的问题。
### 优化
   优惠券搜索功能。
   降低新手布署难度以及升级成本。
   调整微信支付日志级别。
## [1.3.1.2](https://fanyoukeji.net/) - 2020.11.02
### 修复
     易联云打印机，更换开放平台配置后，缓存未及时清除的问题
### 优化
    页面设计功能升级，简化小程序配置参数
## [1.3.1.1](https://fanyoukeji.net/) - 2020.10.31
### 修复
     拼团订单提交失败的问题

## [1.3.1](https://fanyoukeji.net/) - 2020.10.28
### 新增
    会员VIP购买记录
### 修复
     会员VIP购买后未生效的问题
## [1.3.0.1](https://fanyoukeji.net/) - 2020.10.28
### 修复
    自定义布局中修改备注信息不生效
### 优化
    降低定时任务的配置难度   
## [1.3](https://fanyoukeji.net/) - 2020.10.27
### 新增
    增加自定义布局

## [1.2.8](https://fanyoukeji.net/) - 2020.10.22
### 新增
 - 全局配置设置，增加帮助选项，使用户更简易完成商城搭建。
 - 新增找朋友代付功能。
    - TODO 退单，移动端，后台 服务层 ， 订单支付，及回调
 - 添加秒杀、拼团分享图的清除功能。
 - 商品销售详情展示。
 - 打通微信公众号与小程序使用unionId通讯。
### 优化
 - 根据商品查询订单。
### 修复
- 修复拼团列表在某些情况下查询失效的问题。
- 修复商品修改虚拟销量的时候，总销量未跟随改变。
- 用户注册时候生成的userId，如果首字母为in的话，会使用in查询，与查询组键关键字产生冲突。
#### 更新变动



- ##### 数据库 
  -  rf_pag_config  新增refresh_flag字段
  -  rf_proxy_pay  新增数据表(订单代付)
  -  rf_user_commission_detail  新增detail 字段
  -  rf_refund_order_apply  增加pay_order_id字段 
  -  rf_product_stock_detail  增加spec_snap字段   
  -  rf_basic_order_info  增加order_add_snap,paid_user_id字段，为后期商品加价做准备,开通代付功能
```  
 
	ALTER TABLE rf_pag_config ADD COLUMN refresh_flag tinyint(2)  DEFAULT 0  COMMENT '是否自动刷新' ,ADD COLUMN  remark varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL;
    ALTER TABLE rf_refund_order_apply ADD COLUMN pay_order_id varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT '退单前订单状态';
    ALTER TABLE rf_product_stock_detail ADD COLUMN spec_snap varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL COMMENT '商品规格';
    ALTER TABLE rf_user_commission_detail ADD COLUMN detail text CHARACTER SET utf8 COLLATE utf8_general_ci  DEFAULT NULL COMMENT '分润详情';
    ALTER TABLE rf_basic_order_info ADD COLUMN order_add_snap text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '订单加价镜像',ADD COLUMN  paid_user_id varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL DEFAULT NULL ;
    
```

## [1.2.7.1](https://fanyoukeji.net/) - 2020.10.8
### 新增
- 兼容公众号-小程序 会员打通功能。
- 增加微信企业付款到零钱功能未开通情况的判定。  
### 优化
- 图片上传
  - 富文本编辑器内的图片上传返回格式正规化。
- 关闭定时任务请求日志，查询请求不记录日志。
### 修复
  - 积分商城
    - 积分兑换较验规则异常。
  - 打印机
    - 易联云打印机，解决第一次填写配置信息错误后，缓存导致一直报错。    
## [1.2.7](https://fanyoukeji.net/) - 2020.9.29
### 新增
- 打印机
  - 可同时添加两种品牌的打印机。
  - 增加打印自定义LOGO和标题的功能。 
    
### 修复
- 查看资讯详情，取消登陆权限较验
- 清除商品分享图的时候，清除了包含秒杀商品及拼团商品的分享图
- 商品评论
    - 前端显示异常的部分问题
    - 增加后台审核评论的操作。
    
### 优化
- 分享图未登陆时候，跳登陆页面。
- 配置文件添加全局参数： # add since 1.2.7  之后的参数

#### 更新变动
- ##### 数据库 
  -  rf_product_reply  增加status字段
``` 
      ALTER TABLE rf_product_reply ADD COLUMN product_snap text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '商品镜像',ADD COLUMN  status tinyint(2) DEFAULT 0;
```
  -  rf_system_config_tab  增加打印机
```
INSERT INTO `rf_system_config_tab`(`id`, `title`, `eng_title`, `status`, `info`, `icon`, `type`, `show_order`, `created_at`, `updated_at`) VALUES (42, '打印机-易联云', 'yly_print', 1, '0', '1', 0, 1, NULL, NULL);

INSERT INTO `rf_system_config_tab_value` ( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'print_logo', 'text', 'input', '40', NULL,  1, NULL, NULL, NULL, NULL, '商家Logo', '填写方案见: https://www.yuque.com/shfak6/tdfz2z/sy0guz/edit#scX5q', 0, 1, 1598842751, 1598849301);
INSERT INTO `rf_system_config_tab_value` ( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'print_title', 'text', 'input', '40', NULL, NULL, NULL, NULL, NULL, '飞鹅云商城', '商家打印订单抬头', NULL, 0, 1, 1598842751, 1598849301);

INSERT INTO `rf_system_config_tab_value`( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'account_id', 'text', 'input', '42', NULL, NULL, NULL, NULL, NULL, 'clientId', '应用Id', '易联云开放平台自有应用clientId', 0, 1, 1591836298, 1591844306);
INSERT INTO `rf_system_config_tab_value`( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'account_secret', 'text', 'input', '42', NULL, NULL, NULL, NULL, NULL, 'secretKey', '应用密钥', '开放平台发放的密钥', 0, 1, 1591836378, 1591844352);
INSERT INTO `rf_system_config_tab_value`( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ('print_after_pay', 'radio', 'input', '42', NULL, NULL, NULL, NULL, NULL, '1', '支付后打印', '开启时，订单支付完成后打印', 0, 1, 1591945815, 1591945815);
INSERT INTO `rf_system_config_tab_value` ( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'print_logo', 'image', 'input', '42', NULL,  1, NULL, NULL, NULL, NULL, '商家Logo', '只支持格式为 jpg，jpeg，png  的图片，宽度小于384', 0, 1, 1598842751, 1598849301);
INSERT INTO `rf_system_config_tab_value` ( `menu_name`, `type`, `input_type`, `config_tab_id`, `parameter`, `upload_type`, `required`, `width`, `high`, `value`, `info`, `desc`, `show_order`, `status`, `created_at`, `updated_at`) VALUES ( 'print_title', 'text', 'input', '42', NULL, NULL, NULL, NULL, NULL, '易联云商城', '商家打印订单抬头', NULL, 0, 1, 1598842751, 1598849301);
```
- ##### 源文件
   - api\modules\*,api\config\*
   - fanyou/*
#### TODO
- 在购买完商品之后，用户可选择 加价 换取其它服务
    例如 淘宝加10元变顺丰，及运费险。
- 全局 .env 关于分享图，合成图片位置的参数
    在控制后台以图文格式展示。    

## [1.2.6.2](https://fanyoukeji.net/) - 2020.9.23

### 修复
- 存储类型为local时，图片生成路径重定向。
- 存储类型为local时，本地上传文件目录至服务根目录下。
- 定时任务运行包，由api/web目录转移至web目录下。
- 活动类商品 ，关闭活动时同时下架商品，相关商品不会再以普通商品出现。
- 页面装修刷新机制。
 #### 更新变动
 - ##### 数据库 
   -  rf_task_task  定时任务，添加装修刷新
 ```
    INSERT INTO `rf_task_task` VALUES (6, '装修刷新', 1, '', 1, '0 0/10 * * * *', 1, 'http://your/api/seller/tasks/refresh-config', 2, 0, 0, 0, 0, 0, 1, '', '', '', '', 1, '2020-09-20 10:36:36', NULL);
 ```
- ##### 源文件
   - api\modules\seller\controllers.TaskController.php
   - fanyou/*
  
## [1.2.6.1](https://fanyoukeji.net/) - 2020.9.21
 
### 修复
- 商品评价，移动端修改商品评分不生效。
### 优化
- 代理分销，展示用户编号。
- 秒杀列表，关闭秒杀，不需要下架商品了
- 拼团列表，关闭拼团，不需要下架商品了

#### 更新变动
- ##### 数据库 
  -  rf_pink  拼团商品默认生效
  -  rf_sale_strategy   秒杀商品默认生效
```
   ALTER TABLE rf_pink MODIFY COLUMN status tinyint(2) DEFAULT 1;
   ALTER TABLE rf_sale_strategy MODIFY COLUMN on_show tinyint(4) DEFAULT 1;
```

## [1.2.6](https://fanyoukeji.net/) - 2020.9.18

### 优化
- 拼团列表前端页展示优化配置。

## [1.2.5](https://fanyoukeji.net/) - 2020.9.17

### 新增 
- 可定制，连接物联网硬件设备，发放指令

## [1.2.4](https://fanyoukeji.net/) - 2020.9.15
### 新增 
- 增加配置文件.env，开启简易配置模式

### 优化 
- 分享图字体颜色参数，转移到配置文件
- 文件存储配置驱动类型，转移到配置文件


## [1.2.3](https://fanyoukeji.net/) - 2020.9.11
### 新增 
- 商城数据统计
### 优化 
- 商品增加是否退单选项（部分商品购买后，不支持退单）
- 商品增加购买后触发指令操作
 
## [1.2.2](https://fanyoukeji.net/) - 2020.9.09
### 修复 
- 后台排序搜索
    - 商品列表，使用类型搜索不生效。
    - 拼团管理，商品名称搜索不生效。 
    - 核销券记录，日期搜索不生效。
    - 秒杀标题搜索不生效。  
    - 用户以最近登陆时间排序。 
    - 添加以更新时间排序的功能。 

## [1.2.1](https://fanyoukeji.net/) - 2020.9.07
### 修复 
- 拼团模块
    - 拼团结果通知，小程序模版消息未发送成功。  
    - 优化拼团前端展示结构。      
## [1.2.0](https://fanyoukeji.net/) - 2020.9.03
### 新增  
- 拼团模块
    - 发起拼团。 
    - 参与拼团。  
    - 定时任务完结拼团。 
    
### 修复
- 三级分销模块。 
    - 三级分销退单，未记录财务资金退单流水。
    
## [1.1.1](https://fanyoukeji.net/)- 2020.08.1
### 新增  
- 打印机兼容（易联云）。 
    - 下单后，为所有打印机发送打印请求
#### TODO
- 设置打印机打印模版

## [1.1.0](https://fanyoukeji.net/)- 2020.07.06
### 新增  
- 三级分销模块。 
    - 三级分销配置，设置团队管理奖及区域管理奖。 


## [1.0.0](https://fanyoukeji.net/)- 2020.07.05
### 新增  
- 门店核销。 
    - 增加员工端。 
    - 接入员工微信登陆，扩展店铺核销能力。
    
### 修复  
- 订单支付，抵扣积分按照实际支付金额抵扣。
- 配送订单，默认选择收货联系人。   
- 优惠券不能批量发放的问题。 
   
## [0.11.0](https://fanyoukeji.net/)- 2020.06.21
### 新增  
- 装修管理。 
    - 页面装修。 
    - 首页装修。 

 ## [0.10.0](https://fanyoukeji.net/)- 2020.06.18
 ### 新增  
 - 运费模版。
    - 区域单独设置运费。
    - 区域免除运费。     
## [0.9.0](https://fanyoukeji.net/)- 2020.06.8
### 新增  
- 权限管理。  
- 菜单管理。

## [0.8.0](https://fanyoukeji.net/)- 2020.06.5
### 新增  
- 打印机管理（飞鹅）。 
- 秒杀管理。  
- 微信订阅消息。
  
## [0.7.2](https://fanyoukeji.net/)- 2020.05.1 
### 修复  
- 秒杀限购数量未生效。 
## [0.7.2](https://fanyoukeji.net/)- 2020.04.30 
### 新增  
- 秒杀模块。 

## [0.7.1](https://fanyoukeji.net/)- 2020.04.2
### 修复  
- 微信支付订单，退单时，微信平台资金不足时，微信零钱未到帐。

## [0.7.0](https://fanyoukeji.net/)- 2020.03.20
### 新增  
- 用户分销。 
- 分销申请提现。 

## [0.6.0](https://fanyoukeji.net/)- 2020.03.15
### 新增  
- 财务资金监管。 

## [0.5.0](https://fanyoukeji.net/)- 2020.03.12
### 新增 
- 资讯管理。
- 后台会员积分调整。 
## [0.4.1](https://fanyoukeji.net/)- 2020.03.11
### 修复
- 门店Logo可自由更换。
- 门店状态可自行设置。
## [0.4.0](https://fanyoukeji.net/)- 2020.03.10
### 新增 
- 门店管理。
    - 添加门店。
- 商品评论。 
## [0.3.2](https://fanyoukeji.net/)- 2020.03.08
### 修复
- 商品绑定多个分类。
- 商品规格独立条形码。

## [0.3.1](https://fanyoukeji.net/)- 2020.03.07
### 修复
- 后台操作日志异步执行。

## [0.3.0](https://fanyoukeji.net/)- 2020.03.06
### 新增 
- 后台操作日志。
- 订单增加自提类型。

### 修复
- 退单积分不返还的问题。
- 多规格商品在主界面价格显示。

## [0.2.0](https://fanyoukeji.net/)- 2020.03.04
### 新增
- 订单使用优惠券。
- 订单抵扣积分。


## [0.1.0](https://fanyoukeji.net/)- 2020.03.02 

商城第一版上线。