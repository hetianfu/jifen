<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\event\ProductEvent;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductSkuQuery;
use api\modules\seller\models\forms\SaleProductModel;
use api\modules\seller\models\forms\SaleProductQuery;
use api\modules\seller\models\forms\SaleProductStrategyModel;
use api\modules\seller\models\forms\SaleProductStrategyQuery;
use api\modules\seller\models\forms\SaleStrategyModel;
use api\modules\seller\models\forms\SaleStrategyQuery;
use api\modules\seller\service\ProductService;
use api\modules\seller\service\ProductSkuResultService;
use api\modules\seller\service\SaleStrategyService;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\HttpException;


/**
 * SaleStrategy
 * @author E-mail: Administrator@qq.com
 *
 */
class SaleStrategyController extends BaseController
{

    private $service;
    private $productService;
    private $productSkuResultService;
    const EVENT_SAVE_PRODUCT = 'save-product';
    const EVENT_DEL_PRODUCT = 'del-product';
    public function init()
    {
        parent::init();
        $this->service = new SaleStrategyService();
        $this->productService = new ProductService();
        $this->productSkuResultService = new ProductSkuResultService();

        $this->on(self::EVENT_SAVE_PRODUCT,['api\modules\seller\service\event\ProductEventService','saveProduct']);

        $this->on(self::EVENT_DEL_PRODUCT,['api\modules\seller\service\event\ProductEventService','deleteProduct']);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['test-sale-product']
        ];
        return $behaviors;
    }
/*********************SaleStrategy模块控制层************************************/ 
	/**
	 * 添加活动策略
     * @return mixed
     * @throws HttpException
     */
	public function actionAddSaleStrategy()
	{
		$model = new SaleStrategyModel();
		$model->setAttributes($this->getRequestPost()) ;
		if ($model->validate()) {
			return $this->service->addSaleStrategy($model);
		} else {
		   throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
		}
	}
	/**
	 * 分页获取活动策略
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSaleStrategyPage()
	{
		$query = new SaleStrategyQuery();
		$query->setAttributes($this->getRequestGet());
		if ( $query->validate()) {
			return $this->service->getSaleStrategyPage( $query);
		} else {
		   throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
		}
	}
	/**
	 * 根据Id获取活动策略详情
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionGetSaleStrategyById(){
		$id = parent::getRequestId();
		return $this->service->getSaleStrategyById($id);
	}
	/**
	 * 根据Id更新活动策略
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSaleStrategy(){

		$model = new SaleStrategyModel();
		$model->setAttributes($this->getRequestPost(false)) ;
		if ($model->validate()) {
			return $this->service->updateSaleStrategyById($model);
		} else {

		  throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
	   }
	}

	/**
	 * 根据Id删除活动策略
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelSaleStrategyById(){
		$id =parent::getRequestId();

		return $this->service->deleteById($id);
		}

    /**********************End Of SaleStrategy 控制层************************************/
    /**
     * 添加热销商品
     * @return mixed
     * @throws HttpException
     */
    public function actionTestSaleProduct()
    {
      return  $this->saveProduct($this->productService->getOneById('191108160717118'));
    }

    /**
     * 添加热销商品
     * @return mixed
     * @throws HttpException
     */
    public function actionAddSaleProduct()
    {
        $model = new SaleProductModel();
        $model->setAttributes($this->getRequestPost(),false) ;

        if(!$this->service->verifySaleProductAdd($model->id) ){
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '活动商品已经存在');
        }
        if ($model->validate()) {

            $productId=   $this->saveProduct($this->productService->getOneById(Yii::$app->request->post('productId')),$model->name);
           // 添加一条促销商品
            $model->id=$productId;
            $id=$this->service->addSaleProduct($model);
            return $id;
        } else {
            throw new  FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    private function saveProduct( $array,$name){

        $model = new ProductModel();
        $model->setAttributes(ArrayHelper::toArray($array),false) ;
        $oldProductId=$model->id;
        unset($model->id);
        $model->sale_strategy=StrategyTypeEnum::SECKILL;;
        $model->name=$name;
        if($model->is_sku){
            $query=new ProductSkuQuery();
            $query->product_id=$oldProductId;
            $model->sku_list=$this->productService->getProductSkuList($query);

            $product_spec= $this->productSkuResultService->getOneById($oldProductId);
            $skuResult['spec_list'] =json_decode($product_spec['tag_snap']);
            $skuResult['merchant_id']=$model->merchant_id;
           // $skuResult['id']=$oldProductId;
            $model->product_spec=$skuResult;
        }
        $model->category_list=$model->category_id;
        if ($model->validate()) {
            $event=new ProductEvent();
            $categoryList=$model->category_list;
            if(is_array($categoryList)){
                $categoryIds= array_column($categoryList,'id');
                $categoryNames= array_column($categoryList,'name');
                $event->categoryIdList=$categoryIds;
                $model->category_id=json_encode($model->category_id);
                $model->category_snap=json_encode($categoryNames,JSON_UNESCAPED_UNICODE);
            }
            $this->productService->verifyRepeatName($model->merchant_id,$model->name);
            $model->share_img=null;
            if(empty( $model->base_sales_number)){
            $model->base_sales_number=0;
            }
            $model->real_sales_number=0;
            $model->sales_number=$model->base_sales_number+$model->real_sales_number;
            $productId=$this->productService->addProduct($model);
            $event->productId=$productId;
            $this->trigger(self::EVENT_SAVE_PRODUCT,$event);
            return $productId;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed,parent::getModelError($model));
        }
    }

    /**
     * 分页获取 热销商品
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSaleProductPage()
    {
        $query = new SaleProductQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getSaleProductPage( $query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取热销商品详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSaleProductById(){
        $id = Yii::$app->request->get('id');
        return $this->service->getSaleProductById($id);
    }
    /**
     * 根据Id更新热销商品
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateSaleProductById(){

        $model = new SaleProductModel(['scenario'=>'update']);
        $model->setAttributes($this->getRequestPost(false)) ;
        if ($model->validate()) {
            return $this->service->updateSaleProductById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除热销商品
     * @return mixed
     */
    public function actionDelSaleProductById()
    {
        $id =parent::getRequestId();
        $result = $this->service->deleteSaleProductById($id);
        if ($result) {
            $result=$this->productService->deleteProductById($id);
            if($result){
                $event=new ProductEvent();
                $event->productId=$id;
                $this->trigger(self::EVENT_DEL_PRODUCT,$event);
            }
            return $result;
        }
        return $result;
    }


    /**
     * 获取列表
     * @return mixed
     * @throws HttpException
     */
//    public function actionGetSaleProductStrategyList()
//    {
//
//        $query = new SaleProductStrategyQuery();
//        $query->setAttributes($this->getRequestGet());
//        if ( $query->validate()) {
//            $list=$this->service->getSaleProductStrategyList( $query);
//            return $list;
//        } else {
//            throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($query));
//        }
//    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateSaleProductStrategyById(){

        $model = new SaleProductStrategyModel(['scenario'=>'update']);

        $model->setAttributes($this->getRequestPost(false)) ;

        if ($model->validate()) {

            return $this->service->updateSaleProductStrategyById($model);
        } else {
            throw new \yii\web\UnprocessableEntityHttpException(parent::getModelError($model));
        }
    }

}
/**********************End Of SaleStrategy 控制层************************************/ 


