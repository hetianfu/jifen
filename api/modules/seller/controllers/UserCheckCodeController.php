<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\UserCheckCodeQuery;
use api\modules\seller\service\UserCheckCodeService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * UserCoupon
 * @author E-mail: Administrator@qq.com
 *
 */
class UserCheckCodeController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new UserCheckCodeService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-sum']
        ];
        return $behaviors;
    }
/*********************UserCoupon模块控制层************************************/ 

	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserCouponPage()
	{
		$query = new UserCheckCodeQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
            $result=$this->service->getUserCouponPage( $query);
            $result['header']= StringHelper::toCamelize(ArrayHelper::toArray($this->service->sum($query)));
            return $result;
		} else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}


    /**
     * 统计数量
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetSum(){
        $query = new UserCheckCodeQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->sum( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }



	/**
	 * 根据Id获取详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetUserCouponById(){
		$id = Yii::$app->request->get('id');
		return $this->service->getUserCouponById($id);
	}


	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelUserCoupon(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}

    public function actionGetCheckOrderRecordPage()
    {
        $query = new UserCheckCodeQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getCheckOrderRecordPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

    /**
     * 核销卡券
     * @return mixed
     */
    public function actionChangeUserCoupon()
    {   //核销卡券
        //改变核销状态

        return $this->service->verifyBarCode(Yii::$app->request->post('orderId'),Yii::$app->request->post('number')  );
    }

}
/**********************End Of UserCoupon 控制层************************************/ 


