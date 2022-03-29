<?php

namespace api\modules\seller\service\wechat;
use yii\web\UnprocessableEntityHttpException;
use Yii;


/**
 * Class TemplateMsgService
 * @package addons\Wechat\services
 * @author kbdxbt
 */
class MiniAppTemplateMsgService
{
    /**
     * formid保留次数
     * @var int
     */
    public $form_count = 10;

    /**
     * 消息队列
     *
     * @var bool
     */
    public $queueSwitch = false;

    /**
     * 获取模板标题的关键词列表
     * @param $tid
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getKeywords($tid)
    {
        $array=Yii::$app->wechat->miniProgram->subscribe_message->getTemplateKeywords($tid);
        $data=$array['data'];
       // $kids=array_column($data,'kid');
      //  $kidssss=array_keys($data);
        print_r($data);   ;exit;
        return ;
    }

    /**
     * 发送 (发送不成功请先检查系统微信参数是否配置)
     * 微信小程序统一服务消息接口（格式参考文档：https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/uniform-message/uniformMessage.send.html）
     * 微信公众号模板消息（格式参考文档：https://developers.weixin.qq.com/doc/offiaccount/Message_Management/Template_Message_Interface.html）
     *
     * @param $data
     * @return bool
     * @throws UnprocessableEntityHttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function realSend($data)
    {
        try {
            if (isset($data['weapp_template_msg']) || isset($data['mp_template_msg'])) {
                // 微信小程序统一服务消息接口
                $result = Yii::$app->wechat->miniProgram->uniform_message->send($data);
            } else {
                // 微信公众号模板消息
                $result = Yii::$app->wechat->app->template_message->send($data);
            }

            Yii::info($result);
            if ($result['errcode'] != 0) {
                throw new UnprocessableEntityHttpException('模板消息发送失败:' . $result['errcode']);
            }

            return true;
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }


    /**
     * 获取帐号下已存在的模板列表
     * @param $page
     * @param $limit
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplateMsgPage($page=1, $limit=10)
    {
        return  Yii::$app->wechat->miniProgram->template_message->getTemplates($page, $limit); ;
    }


    /**
     * 获取模板库某个模板标题下关键词库
     * @param $id
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTemplateMsgById($id)
    {
        Yii::$app->wechat->miniProgram->template_message->get($id);
        return false;
    }


    /**
     * 添加订阅模版
     * @param $tid
     * @param $kidList
     * @param $sceneDesc
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addSubScribeTemplateMsg($tid, $kidList, $sceneDesc)
    {
        return  Yii::$app->wechat->miniProgram->subscribe_message->addTemplate($tid, $kidList, $sceneDesc);
    }

    /**
     * 获取小程序账号的类目
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSubScribeCategory()
    {
        return  Yii::$app->wechat->miniProgram->subscribe_message->getCategory();
    }
    /**
     * 获取帐号下已存在的订阅模板列表
     * @param $page
     * @param $limit
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSubScribeTemplatePage()
    {

        return  Yii::$app->wechat->miniProgram->subscribe_message->getTemplates();
    }
    /**
     * 删除帐号下的某个模板
     * @param $id
     * @return bool
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteSubscribeTemplateById($id)
    {

        return  Yii::$app->wechat->miniProgram->subscribe_message->deleteTemplate($id);
    }

}