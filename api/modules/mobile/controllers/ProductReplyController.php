<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\service\ProductReplyService;
use api\modules\mobile\models\forms\ProductReplyModel;
use api\modules\mobile\models\forms\ProductReplyQuery;
use fanyou\enums\entity\OrderStatusEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * Class ProductReplyController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-02 9:04
 */
class ProductReplyController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new ProductReplyService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional'=>['get-by-id','get-page','get-recommend-page']
        ];
        return $behaviors;
    }
/*********************ProductReply模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     */
	public function actionAdd()
	{
	    $request=$this->getRequestPost();
        $userId=parent::getUserId();
        $orderId=$request['orderId'];
        $list=$request['list'];

        foreach ($list as $k=>$v){
            $model = new ProductReplyModel();
            $model->setAttributes(StringHelper::toUnCamelize(ArrayHelper::toArray($v)),false);
            $model->user_id= $userId;
            $model->order_id=$orderId;
           // $product=ProductModel::find(['id','name','sale_price','images'])->where($v['productId'])->asArray()->one();
           // print_r($product);exit;
           // $model->product_snap=json_encode($product,JSON_UNESCAPED_UNICODE);


            $this->service->addProductReply($model);
        }
        //评论提交后，将订单状态改为closed
        BasicOrderInfoModel::updateAll(['is_reply'=>StatusEnum::USED],['id'=>$orderId]);
        return count($list) ;
	}

	/**
	 * 分页获取列表
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetPage()
	{
		$query = new ProductReplyQuery();
		$query->setAttributes($this->getRequestGet(false));

		if ( $query->validate()) {
			return $this->service->getProductReplyPage( $query);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
		}
	}
    public function actionGetRecommendPage()
    {
        $query = new ProductReplyQuery();
        $query->setAttributes($this->getRequestGet(false));
        if ( $query->validate()) {

            return $this->service->getProductReplyPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

    /**
     * 根据订单号查询自己的评论
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionGetByOrderId(){
        $query = new ProductReplyQuery();
        if ( $query->validate()) {
            $query->orderId=parent::getRequestId();
            $query->status=null;
            $query->userId=parent::getUserId();
            $query->isRecommend=null;
            return $this->service->getProductReplyList( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
	public function actionGetById(){

		return $this->service->getOneById(parent::getRequestId());
	}
	/**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateById(){

		$model = new ProductReplyModel(['scenario'=>'update']);
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateProductReplyById($model);
		} else {
			throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除
	 * @return mixed
	 */
	public function actionDelById(){

		$id = Yii::$app->request->get('id');
		return $this->service->deleteById($id);
		}
	}
/**********************End Of ProductReply 控制层************************************/ 


