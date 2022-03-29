<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\AuthItemModel;
use api\modules\seller\models\forms\AuthItemQuery;
use api\modules\seller\models\forms\AuthRoleModel;
use fanyou\enums\AppEnum;
use fanyou\enums\NumberEnum;
use fanyou\enums\QueryEnum;
use fanyou\enums\StatusEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\helpers\TreeHelper;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */
class AuthItemService
{

    private $itemChildService;
    public function __construct()
    {
        $this->itemChildService = new AuthItemChildService();

    }
/*********************AuthItem模块服务层************************************/
	/**
	 * 添加一条公用_权限表
	 * @param AuthItemModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addAuthItem(AuthItemModel $model)
	{
        $pid = $model->pid;
        if (!empty($pid)) {
            $parentModel= AuthItemModel::findOne($pid);
            $model->tree = $parentModel->tree . TreeHelper::prefixTreeKey($pid);
        }else{
            $model->tree =TreeHelper::prefixTreeKey(0);
        }
		$model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param AuthItemQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getAuthItemPage(AuthItemQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => AuthItemModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['title'], // 模糊查询
			'defaultOrder' => [
			'sort' => SORT_ASC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search( $query->toArray([],[QueryEnum::LIMIT]) );
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}

    /**
     * 获取菜单列表
     * @param AuthItemQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getAuthItemList(AuthItemQuery $query): array
    {

        $searchModel = new SearchModel([
            'model' => AuthItemModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC
            ],
        ]);

        $searchWord = $searchModel->search( $query->toArray() );
       return ArrayHelper::toArray($searchWord->getModels());
    }

    /**
     * 获取鉴权参数列表
     * @param AuthItemQuery $query
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getCasbinItemList(AuthItemQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => AuthItemModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_ASC
            ],
            'select'=>['service_path','request_method']
        ]);

        $searchWord = $searchModel->search( $query->toArray() );
        return ArrayHelper::toArray($searchWord->getModels());
    }

	/**
	 * 根据Id获取权限表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return AuthItemModel::findOne($id);
	}

	/**
	 * 根据Id更新权限表
	 * @param AuthItemModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateAuthItemById (AuthItemModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除权限表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = AuthItemModel::findOne($id);
		//删除所有下级菜单
        AuthItemModel::deleteAll(['like', 'tree', $model->tree . TreeHelper::prefixTreeKey($id) . '%', false]);
        return  $model->delete();
	}


    /**
     * 根据角色Id获取递归菜单权限
     * @param $roleIds
     * @return array
     * @throws \yii\web\NotFoundHttpException
     */
    public function getAuthItemByRoles($roleIds){

        $itemIds=$this->itemChildService->findItemByRoleIds( $roleIds );

        $pQuery = new AuthItemQuery();
        $pQuery->id=QueryEnum::IN.implode(',',$itemIds);
        $pQuery->pid=NumberEnum::ZERO;
        $ids=array_column($this->getAuthItemList($pQuery),'id');

        $query = new AuthItemQuery();
        $query->id=QueryEnum::IN.implode(',',$ids);
        $query->pid=StatusEnum::STATUS_INIT;

        return $this->recursion( $query,$itemIds);
}

    /**
     * 获取当前角色的菜单
     * @param $roleIds
     * @return array
     */
    public function getCurrencyMenuItem($roleIds){

        $extendItems=AuthRoleModel::findOne($roleIds[0])->extend_item;

        $itemIds=$this->itemChildService->findItemByRoleIds( $roleIds );
        if(!empty($extendItems)){
        $itemIds= array_merge($itemIds,json_decode($extendItems));
        }
        $pQuery = new AuthItemQuery();
        $pQuery->id=QueryEnum::IN.implode(',',$itemIds);
        $pQuery->pid=NumberEnum::ZERO;
        $pQuery->is_menu=StatusEnum::ENABLED;
        $ids=array_column($this->getAuthItemList($pQuery),'id');

        $query = new AuthItemQuery();
        $query->id=QueryEnum::IN.implode(',',$ids);
        $query->pid=StatusEnum::STATUS_INIT;

        return $this->recursion( $query,$itemIds,StatusEnum::ENABLED);
    }


    /**
     * 递归方法
     * @param AuthItemQuery $query
     * @param array $itemIds
     * @param int $onlyMenu  是否只查询菜单，1是，0查询按钮 -1查询所有
     * @return array
     */
    public function recursion(AuthItemQuery $query,$itemIds=[],$onlyMenu=NumberEnum::N_ONE)
    {
        if($onlyMenu!==NumberEnum::N_ONE){
            $query->is_menu=$onlyMenu;
        }
        $list=$this->getAuthItemList( $query);
        if(count($list)){
            foreach ($list as $k=>$v){
                $childQuery = new AuthItemQuery();
                $childQuery->pid=$v['id'];
                if(!empty($itemIds)){
                 $childQuery->id=QueryEnum::IN.implode(',',$itemIds);
                }
                $childList=$this->recursion( $childQuery,$itemIds,$onlyMenu);
                !empty($childList)&&$list[$k]['children']=  $childList ;
            }
        }
        return $list;
    }

    /**
     * 编辑下拉选择框数据
     *
     * @param $app_id
     * @param string $id
     * @return array
     */
    public function getDropDownForEdit($app_id, $id = '')
    {
        $list = AuthItemModel::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['app_id' => $app_id ])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级权限'], $data);
    }

    /**
     * @param array $ids
     * @param string $app_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findByAppId($app_id = AppEnum::BACKEND, $ids = [])
    {
        return AuthItemModel::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['in', 'id', $ids])
            ->select(['id', 'title', 'name', 'pid', 'level', 'app_id', 'is_addon', 'addons_name'])
            ->orderBy('sort asc, id asc')
            ->asArray()
            ->all();
    }


}
/**********************End Of AuthItem 服务层************************************/

