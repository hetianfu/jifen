<?php
namespace fanyou\components\groupDrive;

use api\modules\manage\models\forms\BaseQuery;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\mobile\models\forms\ProductQuery;
use api\modules\mobile\models\forms\SystemGroupModel;
use api\modules\mobile\service\ProductService;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\enums\entity\SystemGroupEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;

/**
 * Class ProductGroup
 * @package fanyou\components\groupDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-06 11:37
 */
class ProductGroup extends GroupInterface
{
    protected $systemGroup;
    protected $productService;
    public function __construct($id,$fields)
    {
        parent::__construct($id,$fields);
    }
    /**
     * 初始化
     */
    protected function create(){

       $this->productService=new ProductService();
    }

    /**
     * 获取商品配置的值
     * @param bool $allShow
     * @param int $page
     * @param int $limit
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getValue($allShow=false,$page=1,$limit=10)
    {
        $query=new ProductQuery();
        $query->id=QueryEnum::IN.$this->fields;
        $query->page=$page;
        $query->limit=$limit;
        if(!$allShow){
            $query->is_on_sale=StatusEnum::ENABLED;
        }
        $searchModel = new SearchModel([
            'model' => ProductModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['name'], // 模糊查询
            'defaultOrder' => [
                'created_at' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search($query->toArray([],[QueryEnum::LIMIT]));
        $result= ArrayHelper::toArray($searchWord->getModels());
        $this->totalCount=$searchWord->pagination->totalCount;
        return $result;
    }
    public function getTotalCount()
    {
       return $this->totalCount;
    }
}

