<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\service\LoginService;
use api\modules\mobile\service\UserInfoService;
use api\modules\seller\models\forms\ChannelModel;
use fanyou\components\PhotoMerge;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use Yii;
use yii\web\HttpException;

/**
 * Class UserController
 * @package api\modules\manage\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-19 14:01
 */
class UserMpController extends BaseController
{

    private $userService;
    private $loginService;

    public function init()
    {
        parent::init();

        $this->userService = new UserInfoService();
        $this->loginService = new LoginService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['login']
        ];
        return $behaviors;
    }


    /**
     * 公众号登陆
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionLogin()
    {
        $unionId = Yii::$app->request->post('unionId');
        $openId = Yii::$app->request->post('openId');
        $nickName = Yii::$app->request->post('nickName');
        $headImg = Yii::$app->request->post('headImg');
        $sourceId = Yii::$app->request->post('sourceId');

      $ChannelModel =   ChannelModel::find()->where(['code'=>$sourceId])->one();
      if ($ChannelModel){
        $sourceId = $ChannelModel->id;
      }else{
        $ChannelModel = UserInfoModel::find()->one();
        $sourceId = $ChannelModel->id;
      }

        if (!empty($unionId)) {
            $userInfo = $this->userService->getOneByUnionId($unionId);

            if (empty($userInfo)) {
                $userInfo = $this->userService->getOneByOpenId($openId);
                if (!empty($userInfo)) {
                    UserInfoModel::updateAll(['union_id' => $unionId], ['id' => $userInfo['id']]);
                }
            }else{
                if(empty($userInfo['open_id'])){
                    UserInfoModel::updateAll(['open_id'=>$openId],['id'=>$userInfo['id']]);
                }
            }
        } else {

            $userInfo = $this->userService->getOneByOpenId($openId);

        }
        if (empty($userInfo)) {

            //为用户注册一个
            $userInfo = new UserInfoModel();
            $userInfo->id = $this->userService->getRandomId();
            //获取微信头像，下载
            if(!empty($headImg)) {
                $headImage = PhotoMerge::saveHeadImg(Yii::$app->params['userImage']['head'], $headImg, $userInfo->id);
                $userInfo->head_img = $headImage;
            }
            $userInfo->nick_name = $nickName;
            $userInfo->union_id = $unionId;
            $userInfo->open_id = $openId;
            $userInfo->last_log_in_at = time();
            $userInfo->amount=0;
            $userInfo->charge_amount=0;

            $channel = ChannelModel::find()->select(['id', 'short_url', 'score', 'short_url', 'name'])->where(['id' => $sourceId])->asArray()->one();
            if (empty($channel)) {
                $channel = ChannelModel::find()->select(['id','code', 'short_url', 'score', 'short_url', 'name'])->asArray()->one();
            }
            if (!empty($channel)) {
                $userInfo->source_id = $channel['id'];
                $userInfo->total_score = $channel['score'];
                $userInfo->source_json = json_encode(['url' => $channel['short_url'], 'name' => $channel['name']], JSON_UNESCAPED_UNICODE);
            }
            $userInfo->id = $this->userService->addUserInfo($userInfo);
        }
        return $this->loginService->createAuthKey($userInfo);
    }

    /**
     * 通过token获取用户登陆信息
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAuthInfo()
    {
        $loginHeader = Yii::$app->request->getHeaders();
        $array = $this->loginService->getAuthInfo($loginHeader['access-token']);
        if (empty($array)) {
            throw new FanYouHttpException(HttpErrorEnum::UNAUTHORIZED, "错误");
        }
        return json_decode($array);

    }


}
/**********************End Of UserInfo 控制层************************************/ 


