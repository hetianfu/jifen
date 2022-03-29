<?php

namespace api\modules\mobile\controllers;

use api\models\User;
use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\CouponUserModel;
use api\modules\mobile\models\forms\CouponUserQuery;
use api\modules\mobile\models\forms\UserAddressModel;
use api\modules\mobile\models\forms\UserAddressQuery;
use api\modules\mobile\models\forms\UserCommissionDetailModel;
use api\modules\mobile\models\forms\UserCommissionDetailQuery;
use api\modules\mobile\models\forms\UserFavoritesModel;
use api\modules\mobile\models\forms\UserFavoritesQuery;
use api\modules\mobile\models\forms\UserInfoModel;
use api\modules\mobile\models\forms\UserInfoQuery;
use api\modules\mobile\models\forms\UserShopCartModel;
use api\modules\mobile\models\forms\UserShopCartQuery;
use api\modules\mobile\models\forms\UserWalletDetailModel;
use api\modules\mobile\models\forms\UserWalletDetailQuery;
use api\modules\mobile\models\UserShopCartResult;
use api\modules\mobile\service\CouponService;
use api\modules\mobile\service\CouponUserService;
use api\modules\mobile\service\UserAddressService;
use api\modules\mobile\service\UserCommissionDetailService;
use api\modules\mobile\service\UserFavoritesService;
use api\modules\mobile\service\UserInfoService;
use api\modules\mobile\service\UserShareService;
use api\modules\mobile\service\UserShopCartService;
use api\modules\mobile\service\UserVipPayService;
use api\modules\mobile\service\UserWalletDetailService;
use api\modules\mobile\service\WechatService;
use api\modules\seller\models\forms\SmsLogModel;
use EasyWeChat\Kernel\Support\Arr;
use fanyou\components\PhotoMerge;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\PayStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\WalletStatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;
use Yii;
use yii\base\BaseObject;
use yii\web\HttpException;

/**
 * Class UserController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-09 17:46
 */
class UserController extends BaseController
{

    private $service;
    private $couponService;
    private $couponUserService;
    private $favoritesService;
    private $shopCartService;
    private $addressService;
    private $miniAppService;
    private $walletDetailService;
    private $commissionDetailService;
    private $userVipPayService;

    public function init()
    {
        parent::init();
        $this->service = new UserInfoService();
        $this->couponService = new CouponService();
        $this->couponUserService = new CouponUserService();
        $this->favoritesService = new UserFavoritesService();
        $this->shopCartService = new UserShopCartService();
        $this->addressService = new UserAddressService();
        $this->miniAppService = new WechatService();
        $this->walletDetailService = new UserWalletDetailService();
        $this->commissionDetailService = new UserCommissionDetailService();
        $this->userVipPayService = new UserVipPayService();

    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-mini-app-code', 'register-user-info', 'get-coupon-page']
            // ,'get-shop-cart-page'
        ];
        return $behaviors;
    }
    /*********************UserInfo模块控制层************************************/


    /**
     * 注册成为会员
     * @return mixed
     * @throws FanYouHttpException
     * @throws FileExistsException
     * @throws FileNotFoundException
     * @throws \yii\base\Exception
     */
    public function actionRegisterUserInfo()
    {
        $model = new UserInfoModel();
        $unionId = Yii::$app->request->post('unionId');
        $miniAppId = Yii::$app->request->post('miniAppOpenId');
        if (empty($unionId)) {
            $userInfo = $this->service->getOneByMiniAppOpenId($miniAppId);
            if (!empty($userInfo)) {
                return StatusEnum::S_SUCCESS;
            }
        } else {
            //如果存在unionId，但未查到相关内容，则使用miniAppId查询
            $userInfo = $this->service->getOneByUnionId($unionId);
            if (empty($userInfo)) {
                $userInfo = $this->service->getOneByMiniAppOpenId($miniAppId);
            }
            //获取微信头像，下载
            if (!empty($userInfo)) {
                $model->id = $userInfo['id'];
                $model->union_id = $unionId;
                $model->mini_app_open_id = $miniAppId;
                $model->last_log_in_at = time();
                return empty($this->service->updateUserInfoById($model)) ? StatusEnum::S_FAIL : StatusEnum::S_SUCCESS;
            }
        }

        $scene = Yii::$app->request->post("sceneId");
        if (!empty($scene)) {
            $shareService = new UserShareService();
            $shareInfo = $shareService->getOneById($scene);
            if (isset($shareInfo['user_id'])) {
                $model->parent_id = $shareInfo['user_id'];
            }
        }

        $model->setAttributes($this->getRequestPost(false));

        $randomId = Yii::$app->getSecurity()->generateRandomString();
        if (strpos($randomId, 'in') === 0) {
            $randomId = str_replace('in', 'R_', $randomId);
        }
        $model->id = $randomId;

        //获取微信头像，下载
        $headImage = PhotoMerge::saveHeadImg(Yii::$app->params['userImage']['head'], $model->head_img, $model->id);
        $model->last_log_in_at = time();
        $model->head_img = $headImage;
        return empty($this->service->addUserInfo($model)) ? StatusEnum::S_FAIL : StatusEnum::S_SUCCESS;
    }


    public function actionSaveMobile(){
      $telephone = Yii::$app->request->post('telephone');
      $code = Yii::$app->request->post('code');

      $log=SmsLogModel::find()->where(['telephone'=>$telephone,'code'=>$code])
        ->andWhere(['>','created_at',time()-300])->asArray()->one();

      if(empty($log)  || ( $log['ali_message'] !== 'OK' )){
        throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '验证码错误，请重新发送！');
      }

      //判断手机号是否已经存在
      $is_mobile = \api\modules\seller\models\forms\UserInfoModel::find()->where(['telephone'=>$telephone])->one();
      if($is_mobile){
        throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '手机号已存在');
      }


      $model = new UserInfoModel(['scenario' => 'update']);
      $model->id = parent::getUserId();
      $model->telephone = $telephone;
      $result = $this->service->updateUserInfoById($model);
      return $result;
    }

    /**
     * 同步用户头像和昵称
     * @return mixed
     * @throws FanYouHttpException
     * @throws FileExistsException
     * @throws FileNotFoundException
     */
    public function actionAsyncWechatInfo()
    {
        $token = Yii::$app->request->getHeaders()['access-token'];
        $cache = parent::getCache($token);
        $model = new UserInfoModel(['scenario' => 'update']);
        $model->id = parent::getUserId();
        //获取微信头像，下载
        $headImage = PhotoMerge::saveHeadImg(Yii::$app->params['userImage']['head'], Yii::$app->request->post('headImg'), $model->id);
        $model->head_img = $headImage;
        $model->nick_name = Yii::$app->request->post('nickName');
        $result = $this->service->updateUserInfoById($model);
        if ($result) {
            //重置缓存
            $array = ArrayHelper::toArray(json_decode($cache));
            $array['headImg'] = $model->head_img;
            $array['nickName'] = $model->nick_name;
            parent::setCache($token, $array, 3600);
        }
        return $result;
    }

    /**
     * 获取用户信息
     * @return mixed
     * @throws HttpException
     */
    public function actionGetInfo()
    {
        $request = parent::getRequestGet();

        $tokenId = SystemConfigEnum::REDIS_USER_INFO . $request['userId'];
        $tokenContent = Yii::$app->cache->get($tokenId);
        if (!empty($tokenContent)) {
            return json_decode($tokenContent);
        }
        $result = ArrayHelper::toArray($this->service->getOneById($request['userId']));
        Yii::$app->cache->set($tokenId, json_encode($result), 600);
        return $result;
    }

    /**
     * 推广列表
     * @return mixed
     */
    public function actionGetDisciplePage()
    {
        $query = new UserInfoQuery();
        $query->parent_id = parent::getUserId();
        return $this->service->getDisciplePage($query);
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateInfoById()
    {
        $userCache = Yii::$app->user->identity;
        $userId = $userCache['id'];
        $model = new UserInfoModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        $model->id = $userId;
        if ($model->validate()) {
            return $this->service->updateUserInfoById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /*********************我的收藏********************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddFavorites()
    {
        $model = new UserFavoritesModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->favoritesService->addUserFavorites($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetFavoritesPage()
    {
        $query = new UserFavoritesQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->favoritesService->getUserFavoritesPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelFavoritesById()
    {

        $id = Yii::$app->request->get('id');
        return $this->favoritesService->deleteFavoritesById($id);
    }

    /**********************购物车***************************/

    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddShopCart()
    {
        $model = new UserShopCartModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            if (empty($model->sku_id)) {
                $model->sku_id = $model->product_id;
            }
            return $this->shopCartService->addUserShopCart($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取购物车列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetShopCartPage()
    {
        $query = new UserShopCartQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $userShopCartResult = $this->shopCartService->getUserShopCartPage($query);
            foreach ($userShopCartResult['list'] as $key => $val) {
                $resultModel = new UserShopCartResult();
                $resultModel->setAttributes($val, false);
                $resultModel->productId = $val['product_id'];
                $userShopCartResult['list'][$key] = $resultModel->toArray();
            }
            return $userShopCartResult;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetShopCartById()
    {
        $id = Yii::$app->request->get('id');
        $resultModel = new UserShopCartResult();
        $resultModel->setAttributes(ArrayHelper::toArray($this->shopCartService->getOneById($id)), false);
        return $resultModel->toArray();
    }

    /**
     * 更新购物车
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionUpdateShopCartById()
    {

        $model = new UserShopCartModel(['scenario' => 'update']);
        $model->setAttributes(parent::getRequestPost(), false);
        if ($model->validate()) {
            return $this->shopCartService->updateUserShopCartById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     *  修改购物车数量
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateShopCartNumber()
    {

        $model = new UserShopCartModel(['scenario' => 'update']);
        $model->id = Yii::$app->request->post("id");
        $model->number = Yii::$app->request->post("number");
        if ($model->validate()) {
            return $this->shopCartService->updateUserShopCartNumber($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }


    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelShopCartById()
    {

        $id = Yii::$app->request->get('id');
        return $this->shopCartService->deleteById($id);
    }


    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelShopCart()
    {

        $ids = Yii::$app->request->post('ids');
        return $this->shopCartService->deleteByIds(ArrayHelper::toArray($ids));
    }


    /**
     *  领取优惠券
     * @return mixed
     * @throws HttpException
     */
    public function actionAddCoupon()
    {
        $request = parent::getRequestPost();
        $couponId = $request['coupon_id'];
        $userId = $request['user_id'];
        $coupon = $this->couponService->getEffectOneCoupon($couponId, $userId);

        $model = new CouponUserModel();
        $model->setAttributes(ArrayHelper::toArray($coupon), false);
        $model->user_snap = $request['userSnap'];

        if ($model->validate()) {
            //如果限数量 ： 将卡券剩余数量减1
            $result = $this->couponService->minusCouponNumberById($couponId);
            if ($result) {
                $result = $this->couponUserService->addCouponUser($model);
            }
            return $result;

        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 获取用户可领取的卡券列表
     * @return mixed
     */
    public function actionGetCouponPage()
    {
        $query = new CouponUserQuery();
        $query->setAttributes($this->getRequestGet());
        $query->isOnce = StatusEnum::ENABLED;
        $query->status = StatusEnum::ENABLED;
        return $this->couponService->getCouldGetCouponPage($query);
    }

    /**
     * 展示我已领取的优惠券
     * @return mixed
     * @throws HttpException
     */
    public function actionGetMyCouponPage()
    {
        $query = new CouponUserQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->couponService->getCouponUserPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetCouponById()
    {

        return $this->couponService->getCouponUserById(parent::getRequestId());
    }

    /**
     * 删除
     * @return mixed
     */
    public function actionDelCouponById()
    {
        return $this->couponService->delCouponUserById(parent::getRequestId());
    }

    /*******************地址管理***************************/

    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddAddress()
    {
        $model = new UserAddressModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->addressService->addUserAddress($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAddressPage()
    {
        $query = new UserAddressQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->addressService->getUserAddressPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    public function actionGetAddressList()
    {
        $query = new UserAddressQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->addressService->getUserAddressList($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetAddressById()
    {
        $id = Yii::$app->request->get('id');
        return $this->addressService->getUserAddressById($id);
    }

    /**
     * 设置为默认地址
     * @return mixed
     * @throws HttpException
     */
    public function actionSetDefaultAddress()
    {

        $model = new UserAddressModel();
        $model->setAttributes($this->getRequestPost(true));
        if ($model->validate()) {
            return $this->addressService->setAsDefaultAddress($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateAddressById()
    {

        $model = new UserAddressModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->addressService->updateUserAddressById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelAddressById()
    {

        $id = Yii::$app->request->get('id');
        return $this->addressService->deleteById($id);
    }

    /********************************用户资金流水***********************************/
    /**
     * 申请提现至微信
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionApplyDrawToWx()
    {
        $model = new UserCommissionDetailModel();
        $model->setAttributes($this->getRequestPost());

        $model->is_deduct = StatusEnum::COME_OUT;
        $model->type = WalletStatusEnum::DRAW;
        $model->status = StatusEnum::STATUS_INIT;
        $model->pay_type = PayStatusEnum::WX;
        $model->open_id = parent::getMiniAppOpenId();
        if ($model->validate()) {

            $model = $this->commissionDetailService->verifyApplyDraw($model);
            if (empty($model)) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '提现申请不通过');
            }
            return $this->commissionDetailService->applyToDraw($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 申请提现至钱包
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionApplyDrawToWallet()
    {
        $model = new UserCommissionDetailModel();
        $model->setAttributes($this->getRequestPost());

        $model->is_deduct = StatusEnum::COME_OUT;
        $model->type = WalletStatusEnum::DRAW;
        $model->status = StatusEnum::STATUS_INIT;
        $model->pay_type = PayStatusEnum::WALLET;
        if ($model->validate()) {
            $model = $this->commissionDetailService->verifyApplyDraw($model);
            if (empty($model)) {
                throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '提现申请不通过');
            }
            return $this->commissionDetailService->applyToDraw($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 佣金列表
     * 废弃 2020-6-22
     * @return mixed
     * @throws FanYouHttpException
     * @see UserCommissionController->actionGetDetailPage()
     */
    public function actionGetCommissionPage()
    {
        $query = new UserCommissionDetailQuery();
        $query->setAttributes($this->getRequestPost());
        if ($query->validate()) {
            return $this->commissionDetailService->getUserCommissionDetailPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetWalletDetailPage()
    {
        $query = new UserWalletDetailQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->walletDetailService->getUserWalletDetailPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetWalletDetailById()
    {
        $id = parent::getRequestId();
        return $this->walletDetailService->getUserWalletDetailById($id);
    }


    /**
     * 用户充值
     * @return mixed
     * @throws HttpException
     */
    public function actionCharge()
    {
        $model = new UserWalletDetailModel();
        $model->setAttributes($this->getRequestPost());
        $model->type = WalletStatusEnum::CHARGE;
        $model->is_deduct = StatusEnum::COME_IN;
        if ($model->validate()) {
            return $this->walletDetailService->addUserWalletDetail($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 用户充值终身VIP
     * @return mixed
     */
    public function actionPermanentVipPay()
    {
        return $this->userVipPayService->getPermanentOne();

    }

    public function actionCountVipPay()
    {
        return $this->userVipPayService->count(true);

    }
}
/**********************End Of UserInfo 控制层************************************/ 


