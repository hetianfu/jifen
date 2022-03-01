<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\WechatMessageModel;
use api\modules\mobile\models\forms\WechatMessageQuery;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */
class WechatMessageService
{


    /*********************WechatMessage模块服务层************************************/
    /**
     * 添加一条模版消息
     * @param WechatMessageModel $model
     * @return mixed
     * @throws \Throwable
     */
    public function addWechatMessage(WechatMessageModel $model)
    {
        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * 分页获取列表
     * @param WechatMessageQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getWechatMessagePage(WechatMessageQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => WechatMessageModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
            'pageSize' => $query->limit,
        ]);
        $searchWord = $searchModel->search(array_filter($query->toArray()));
        $result['list'] = ArrayHelper::toArray($searchWord->getModels());
        $result['totalCount'] = $searchWord->pagination->totalCount;
        return $result;
    }

    /**
     * 根据Id获取配置
     * @param $id
     * @return Object
     */
    public function getOneById($id)
    {
        return WechatMessageModel::findOne($id);
    }

    /**
     * 根据Id获取配置
     * @param $templateId
     * @return Object
     */
    public function getOneByTemplateId($templateId): ?WechatMessageModel
    {
        return WechatMessageModel::findOne(['template_id' => $templateId]);
    }

    /**
     * 根据Id更新配置
     * @param WechatMessageModel $model
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateWechatMessageById(WechatMessageModel $model): int
    {
        $model->setOldAttribute('id', $model->id);
        return $model->update();
    }

    /**
     * 根据Id删除配置
     * @param $id
     * @return int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id): int
    {
        $model = WechatMessageModel::findOne($id);
        return $model->delete();
    }

    /**
     * 发送订阅消息
     * @param  $templateId
     * @param $openId
     * @param $data
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSubscribeMessage($templateId,$openId,$data=[])
    {
        $message=$this->messageService->getOneByTemplateId($templateId);
        $keyArray= \fanyou\tools\ArrayHelper::toArray($message->keys);
        $array=[];
        foreach ($data as $k=>$v){
            $a=[];
            $a[$keyArray[$k]]=['value'=>$v];
            $array[]=$a;
        }
        $sendData = [
            'template_id' => $templateId,
            'touser' =>$openId,     // 接收者（用户）的 openid
            'page' => $message->page,       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => $array,         // 模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
        ];
        return   $this->realSendMessage($sendData) ;
    }

    /**
     * 发送支付消息
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPayMsg()
    {
        $templateId = 'bDmywsp2oEHjwAadTGKkUJ-eJEiMiOf7H-dZ7wjdw80';// 支付模版

        $data = [
            'template_id' => $templateId, // 所需下发的订阅模板id
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
        return $this->realSendMessage($data);
    }
    /**
     * 发送退单消息
     * @param $data
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRefundMsg($refundId)
    {
        $templateId = 'bDmywsp2oEHjwAadTGKkUJ-eJEiMiOf7H-dZ7wjdw80';// 退单模版
        $refundId;

        $data = [
            'template_id' => $templateId, // 所需下发的订阅模板id
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
        return $this->realSendMessage($data);
    }
    /**
     * 发送订阅消息
     * @param $data
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function realSendMessage($data)
    {
        return   Yii::$app->wechat->miniProgram->subscribe_message->send($data);

    }
    }
/**********************End Of WechatMessage 服务层************************************/

