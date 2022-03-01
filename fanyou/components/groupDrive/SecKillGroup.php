<?php
namespace fanyou\components\groupDrive;

use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\ProductSkuModel;
use api\modules\mobile\models\forms\SaleProductModel;
use api\modules\mobile\service\SaleProductStrategyService;
use api\modules\seller\models\forms\SaleProductQuery;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * Class SecKillGroup
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020/6/1 0001 : 10:57
 */
class SecKillGroup extends GroupInterface
{
    protected $systemGroup;
    protected $saleProductService;
    public function __construct($id,$fields)
    {
       parent::__construct($id,$fields);
    }
    /**
     * 初始化
     */
    protected function create(){
       $this->saleProductService=new SaleProductStrategyService();
    }

    /**
     *  获取活动属性的值
     * @param bool $allShow
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getValue($allShow=false,$page=1,$limit=10)
    {
        $query=new SaleProductQuery();

        $query->id=QueryEnum::IN.$this->fields;
        $query->page=$page;
        $query->limit=$limit;

          if(!$allShow){
              $query->on_show=StatusEnum::ENABLED;
            }
            $searchModel = new SearchModel([
                'model' => SaleProductModel::class,
                'scenario' => 'default',
                'partialMatchAttributes' => ['name'], // 模糊查询
                'defaultOrder' => [
                    SortEnum::SHOW_ORDER => SORT_ASC
                ],
            ]);

        $searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
        $result= ArrayHelper::toArray($searchWord->getModels());

            foreach ($result as $k=>$v){
                $product=ProductSkuModel::find()
                    ->select(['origin_price','sale_price','member_price', 'stock_number'])// 'images',
                    ->where(['product_id'=>$v['id']])
                    ->asArray()
                    ->one();
                $result[$k]=array_merge($v,$this->saleProductService->getTodayEffectTimeById($v));
                $result[$k]['origin_price']=$product['origin_price'];
                $result[$k]['sale_price']=$product['sale_price'];
                $result[$k]['member_price']=$product['member_price'];
                $result[$k]['stock_number']=empty($product['stock_number'])?0:$product['stock_number'];
                $productInfo= ProductModel::find()->select(['images','sales_number','name','sale_strategy'])->where(['id'=>$v['id']])->asArray()->one();
                $result[$k]['sale_strategy']= $productInfo['sale_strategy'];
                $result[$k]['sales_number']= $productInfo['sales_number'];
                $result[$k]['product_name']= $productInfo['name'];
                $result[$k]['images']= json_decode($productInfo['images']) ;
            }
         return  $result;
    }
    public function getTotalCount()
    {
        return $this->totalCount;
    }
}

