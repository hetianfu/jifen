<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\WechatMessageConfigModel;
use api\modules\mobile\models\forms\WechatMessageModel;
use api\modules\mobile\service\WechatService;
use fanyou\enums\StatusEnum;
use yii;

/**
 * 微信消息
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 */
class WxMessageController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();

        $this->service=new WechatService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-msg-scene', ]
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/

    /**
     * 获取微信订阅消息
     * 支付场景
     * @return mixed
     */
    public function actionGetMsgScene()
    {
        $list=WechatMessageConfigModel::findAll(['status'=>StatusEnum::ENABLED]);
        $result=[];
        if(count($list)){
            foreach ($list as $k=>$v){
            $array= WechatMessageModel::find()->select('template_id')->where(['in','id',explode(',',$v['message_ids']) ])->asArray()->all();
            if(count($array)){
                $result[$v['name']]=array_column($array,'template_id');
            }
            }
        }
        return $result;

    }

    /**
     * 发送支付消息---测试
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSendPayMsg()
    {
        $data = [
            'template_id' => 'rREobFRXnscIke4x4e_NPGtCPJQAwuNY34GabQV8LO0', // 所需下发的订阅模板id
            'touser' => 'o3bOq5af-UgK27DPS0Kb_XyNNA9I',     // 接收者（用户）的 openid
            'page' => '',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                'thing1' => [
                    'value' => '你刚买东西了----',
                ],
                'thing2' => [
                    'value' => 1000,
                ],
                'name3' => [
                    'value' => '你又不想买了?',
                ],
                'character_string6' => [
                    'value' => '100007',
                ]
            ],
        ];
       return Yii::$app->wechat->miniProgram->subscribe_message->send($data);

    }

    /**
     * 发送退单消息
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSendRefundMsg()
    {
        $data = [
            'template_id' => 'bDmywsp2oEHjwAadTGKkUJ-eJEiMiOf7H-dZ7wjdw80', // 所需下发的订阅模板id
            'touser' => 'oSyZp5OBNPBRhG-7BVgWxbiNZm',     // 接收者（用户）的 openid
            'page' => '',       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => [         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
                'date01' => [
                    'value' => '2019-12-01',
                ],
                'number01' => [
                    'value' => 10,
                ],
            ],
        ];

        $this->service->realSendMessage($data);

    }

	}
/**********************End Of UserInfo 控制层************************************/


