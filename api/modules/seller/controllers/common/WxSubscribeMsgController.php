<?php

namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\WechatMessageModel;
use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\forms\WechatMessageQuery;
use api\modules\seller\service\wechat\MiniAppTemplateMsgService;
use api\modules\seller\service\WechatMessageService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;

/**
 * 小程序模版消息
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class WxSubscribeMsgController extends BaseController
{
    private $service;
    private $wMessageService;
    public function init()
    {
        parent::init();
        $this->service = new MiniAppTemplateMsgService();
        $this->wMessageService=new  WechatMessageService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
             'optional' => ['add','get-category' ]
        ];
        return $behaviors;
    }

    /**
     * 获取当前帐号下的个人模板列表
     * @return mixed
     * @throws \Throwable
     */
    public function actionGetCategory()
    {
        return  $this->service->getSubScribeCategory();
    }

    /**
     * 添加模版
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionAdd()
    {
        $model=new  WechatMessageModel();
        $model->setAttributes($this->getRequestPost(),false) ;
        $list=$this->service->getSubScribeTemplatePage();
        if(count($list['data'])){
        foreach ($list['data'] as $k=>$v){
           if($v['priTmplId']==$model->template_id){
            $model->title=$v['title'];
            $model->content=$v['content'];
           }
        }
        }else{
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'模版不存在');
        }

        return  $this->wMessageService->addWechatMessage($model);
    }

    /**
     * 更新消息模版
     */
    public function actionUpdateById()
    {  $model=new WechatMessageModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(),false) ;
        $list=$this->service->getSubScribeTemplatePage();
        if(count($list['data'])){
            foreach ($list['data'] as $k=>$v){
                if($v['priTmplId']==$model->template_id){
                    $model->title=$v['title'];
                    $model->content=$v['content'];
                }
            }
        }else{
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'模版不存在');
        }
        return $this->wMessageService->updateWechatMessageById($model);
    }

    /**
     * 获取当前帐号下的个人模板列表
     * @return mixed
     * @throws \Throwable
     */
    public function actionGetPage()
    {
        $model=new WechatMessageQuery();
        $model->setAttributes($this->getRequestGet(),false) ;
        return  $this->wMessageService->getWechatMessagePage($model);
    }

    /**
     * 删除帐号下的某个模板
     */
    public function actionDelById()
    {
        return $this->wMessageService->deleteById(parent::getRequestId());
    }

    /**
     * 发送模版消息
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSendTemplateMsg()
    {
       $userInfo = Yii::$app->user->identity;

       $data=[
        'touser' =>$userInfo['miniAppOpenId'],
        'template_id' => Yii::$app->request->get("templateId"),
        'url' => '',
        'form_id' => 'form-id',
        'page' => 'index',
        'data' => [
            'content' => '123456',
        ],
        'miniprogram' => '',
        'weapp_template_msg' =>  true ,

       ];
        return $this->service->send($data);
    }
    /**
     * 组合模板并添加至帐号下的个人模板库
     * @return mixed
     */
    public function actionTestAddSubScribe()
    {
        //rREobFRXnscIke4x4e_NPGtCPJQAwuNY34GabQV8LO0
        $tid = 582;     // 模板标题 id，可通过接口获取，也可登录小程序后台查看获取
        $kidList = [1, 2,3,6];      // 开发者自行组合好的模板关键词列表，可以通过 `getTemplateKeywords` 方法获取
        $sceneDesc = '提示用户图书到期';    // 否 服务场景描述，非必填
        return  $this->service->addSubScribeTemplateMsg($tid, $kidList, $sceneDesc);

    }

    /**
     * 重组模版数据
     * @param $value
     * @return array
     * @throws \Throwable
     */
    private function recombo($value)
    {
        $templateId=$value['priTmplId'];
        $title= $value['title'];
        $data=$value['content'];
        $array=explode(".DATA}}",$data);
        $a=[];
        foreach ($array as $k=>$v){
            $n= stripos($v,"{{")+2;
            $a[]=substr($v,$n);

        }
        $message=new  WechatMessageModel();
        $message->template_id=$templateId;
        $message->title=$title;
        $message->keys=json_encode($a);
        $message->content=json_encode($value);
        $message->key_word_snap=json_encode($data);
        $message->insert();
        return  $a;
    }

}