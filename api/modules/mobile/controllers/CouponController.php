<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\service\CouponService;
use api\modules\mobile\models\forms\CouponQuery;

use yii\web\HttpException;

/**
 *  Coupon
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 *
 */
class CouponController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new CouponService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-coupon-page']
        ];
        return $behaviors;
    }
/*********************Coupon模块控制层************************************/


	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetCouponPage()
	{

        return  $this->service->getEffectCouponAfterPay(1)  ;

		$query = new CouponQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getCouponPage( $query);
		} else {
		   throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
		}
	}

	}
/**********************End Of Coupon 控制层************************************/ 


