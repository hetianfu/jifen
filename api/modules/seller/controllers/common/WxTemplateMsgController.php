<?php
//
//namespace api\modules\seller\controllers\common;
//
//use api\modules\seller\controllers\BaseController;
//use api\modules\seller\models\wechat\Menu;
//use api\modules\seller\service\wechat\MenuService;
//use common\helpers\ResultHelper;
//use Yii;
//
///**
// * 自定义模版
// *
// * Class MenuController
// * @package addons\Wechat\merchant\controllers
// * @author jianyuan <admin@163.com>
// */
//class WxTemplateMsgController extends BaseController
//{
//    private $service;
//    public function init()
//    {
//        parent::init();
//        $this->service = new MenuService();
//
//    }
//
//    /**
//     * 设置微信行业信息
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function actionSetWxIndustry()
//    {
//        $industryOne= Yii::$app->request->post("industryOne");
//        $industryTwo= Yii::$app->request->post("industryTwo");
//        return Yii::$app->wechat->app->template_message->setIndustry($industryOne, $industryTwo);
//    }
//
//    /**
//     * 获取设置的行业信息
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function actionGetWxIndustry()
//    {
//        return Yii::$app->wechat->app->template_message->getIndustry();
//    }
//
//    /**
//     * 添加模板
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function actionAddWxTemplateList()
//    {
//        $shortId= Yii::$app->request->get("templateId");
//        return Yii::$app->wechat->app->template_message->addTemplate($shortId);
//    }
//
//    /**
//     * 删除微信模版
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function actionDelWxTemplateById()
//    {   $id= Yii::$app->request->get("id");
//        return Yii::$app->wechat->app->template_message->deletePrivateTemplate($id);
//    }
//
//    /**
//     * 发送模版消息
//     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
//     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public function actionSendWxTemplateMsg()
//    {
//        //WAyyBmkkfMfIGkIu0Vs7IGIuew4Y820r3Cpau6toI5U
//        //WAyyBmkkfMfIGkIu0Vs7IGIuew4Y820r3Cpau6toI5U
//
//       $data=[
//        'touser' =>Yii::$app->request->get("openId"),
//        'template_id' => Yii::$app->request->get("templateId"),
//        'url' => '',
//        'data' => [
//            'content' => '123456',
//        ],
//        'miniprogram' => '',
//       ];
//
//        return Yii::$app->wechat->app->template_message->send($data);
//    }
//
//
//}