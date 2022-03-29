<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ProductCategoryQuery;
use api\modules\seller\models\forms\ProductQuery;
use api\modules\seller\models\forms\ProductStockDetailQuery;
use api\modules\seller\models\forms\ProductStockQuery;
use api\modules\seller\service\ProductCategoryService;
use api\modules\seller\service\ProductService;
use api\modules\seller\service\ProductStockService;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\QueryEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;

/**
 * zl_product_stock
 * @author E-mail: Administrator@qq.com
 * Create Time: 2019/05/05
 */
class ProductStockController extends BaseController
{

    private $service;
    private $productService;
    private $cpService;
    public function init()
    {
        parent::init();
        $this->service = new ProductStockService();
        $this->productService = new ProductService();
        $this->cpService=new ProductCategoryService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
         //  'optional' => ['get-product-stock-rank']
        ];
        return $behaviors;
    } 
/*********************ProductStock模块控制层************************************/ 
	/**
	 * 添加一条内容  
     * @return mixed
     * @throws HttpException
     */
public function actionEnterProductStock()
{

    $storeInfo = Yii::$app->user->identity;
    $ProductStockInfo = new ProductStockEntity();
    $ProductStockInfo->attributes = Yii::$app->request->post();
    $ProductStockInfo->storeId =$storeInfo['storeId']; 
    if ($ProductStockInfo->validate()) {
        //清缓存
        $this->service->cleanStockRedis($storeInfo['storeId']);

        $result=$this->service->enterProductStock($ProductStockInfo);
        $this->sendLogWarning($ProductStockInfo->toArray());
        return $result;
    } else {
        throw new HttpException(417,   parent::getModelError($ProductStockInfo));
    }
}

    public function actionOutProductStock()
    {	$storeInfo = Yii::$app->user->identity;
        $ProductStockInfo = new ProductStockEntity();
        $ProductStockInfo->attributes = Yii::$app->request->post();
        $ProductStockInfo->storeId =$storeInfo['storeId'];
        if ($ProductStockInfo->validate()) {
            //清缓存

            $this->service->cleanStockRedis($storeInfo['storeId']);
           /* $this->productService->cleanProductRedis($storeInfo['storeId']);
            $this->skuService->cleanAllSkuRedis($storeInfo['storeId'] );*/

            $this->sendLogWarning($ProductStockInfo->toArray());
            return $this->service->outProductStock($ProductStockInfo);
        } else {
            throw new HttpException(417,   parent::getModelError($ProductStockInfo));
        }
    }
	/**
	 * 分页获取对象列表
	 * @return mixed
	 * @throws HttpException
	 */
public function actionGetProductStockPage()
{

    $queryProductStock = new ProductStockQuery();
    $queryProductStock->attributes =parent::getRequestGet();
    if ( $queryProductStock->validate()) {

        $productQuery=new ProductQuery();
        $categoryId=$queryProductStock->category_id;
        if( !empty($categoryId) ){
                $cpQuery = new ProductCategoryQuery();
                $cpQuery->category_id=QueryEnum::IN.implode(',',$categoryId);
                $productIds=$this->cpService->getProductIds($cpQuery);

                if(empty($productIds)){
                    return parent::getEmptyPage();
                }
                $productQuery->id=QueryEnum::IN.$productIds;
            }
            if(isset($queryProductStock->product_id )){
                if( !empty($categoryId) )  {
                      if(!  in_array($queryProductStock->product_id ,$this->productService->getProductIds( $productQuery))) {
                          return parent::getEmptyPage();
                      }else{
                          $queryProductStock->product_id=$productQuery->id;
                      }
                }
            }
        unset($queryProductStock->category_id);

        return $this->service->getProductStockPage( $queryProductStock);
    } else {
        throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,   parent::getModelError( $queryProductStock));
    }
}

    /**
     * 获取全部对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductListForStock()
    {

        $queryProductStock = new ProductStockQuery();
        $queryProductStock->attributes =parent::getRequestGet();

        if ( $queryProductStock->validate()) {
            return $this->service->getProductStockPage( $queryProductStock);
        } else {
            throw new HttpException(417,   parent::getModelError( $queryProductStock));
        }
    }
	/**
	 * 获取全部对象列表
	 * @return mixed
	 * @throws HttpException
	 */
public function actionGetProductStockList()
	{
		$queryProductStock = new ProductStockQuery(['scenario'=>'update']); 
		$queryProductStock->setAttributes(parent::getRequestGet());
		$queryProductStock->merchant_id =parent::getMerchantId();
		
		if ( $queryProductStock->validate()) {
		    return $this->service->getProductStockList( $queryProductStock);
		} else {
		    throw new HttpException(417,   parent::getModelError( $queryProductStock));
		}
	}

	/**
	 * 根据Id获取单条对象
	 * @return mixed
	 * @throws HttpException
	 */
public function actionGetProductStockById(){ 
    $id = Yii::$app->request->get('id');
    return $this->service->getProductStockById($id);
}

	/**
	 * 更新对象
	 * @return mixed
	 * @throws HttpException
	 */
public function actionUpdateProductStock(){
   $storeInfo = Yii::$app->user->identity;
    $updateProductStockModel = new ProductStockModel(['scenario'=>'update']); 
    $updateProductStockModel->attributes = Yii::$app->request->post();
    $updateProductStockModel->storeId=$storeInfo['storeId'];
    if ($updateProductStockModel->validate()) {
        //清缓存
        $this->service->cleanOneStockRedis($storeInfo['storeId'],$updateProductStockModel->id);
        $this->sendLogWarning($updateProductStockModel->toArray());
        return $this->service->updateProductStock($updateProductStockModel);
    } else {
        throw new HttpException(417,   parent::getModelError($updateProductStockModel));
    }
}

	/**
	 * 根据Id删除对象
	 * @return mixed
	 * @throws HttpException
	 */
public function actionDelProductStock(){
    $storeInfo = Yii::$app->user->identity;
    $id = Yii::$app->request->get('id');
    $shopId=$storeInfo['storeId'];
    $this->service->cleanOneStockRedis($shopId,$id);
    $this->sendLogWarning($id);
    return $this->service->deleteProductStock($id,$shopId);
    
 	}



    /*********************ProductStockDetail模块服务层************************************/

    /**
     * 分页获取对象列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductStockDetailPage()
    {
        $queryProductStockDetail = new ProductStockDetailQuery();
        $queryProductStockDetail->setAttributes(parent::getRequestGet());

        if ( $queryProductStockDetail->validate()) {

            $sumSplit= $this->service->sumStockDetailAmount($queryProductStockDetail);

            $result=$this->service->getProductStockDetailPage( $queryProductStockDetail);
            $result['header']=StringHelper::toCamelize(ArrayHelper::toArray($sumSplit));
            return $result;
        } else {
            throw new HttpException(417,   parent::getModelError( $queryProductStockDetail));
        }
    }
    /**
     * 根据Id获取单条对象
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductStockDetailById(){
        $id = Yii::$app->request->get('id');
        return $this->service->getProductStockDetailById($id);
    }

    public function actionGetDistinctOrderIdListByProductId(){
        $id = Yii::$app->request->get('productId');
        return $this->service->getProductStockDetailById($id,Yii::$app->request->get('page'),Yii::$app->request->get('limit'));
    }




    /**
     * 商品销售排行
     * @return mixed
     * @throws HttpException
     */
    public function actionGetProductStockRank(){
        $queryProductStockDetail = new ProductStockDetailQuery();
        $queryProductStockDetail->setAttributes(parent::getRequestGet());
        if ( $queryProductStockDetail->validate()) {

          //  $sumSplit= $this->service->sumStockDetailAmount($queryProductStockDetail);
            $result=$this->service->getProductStockRank( $queryProductStockDetail);
           // $result['header']=StringHelper::toCamelize(ArrayHelper::toArray($sumSplit));
            return $result;
        } else {
            throw new HttpException(417,   parent::getModelError( $queryProductStockDetail));
        }
    }

}	
/**********************End Of ProductStock 控制层************************************/ 
 

