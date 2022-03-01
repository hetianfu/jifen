<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\AuthAssignmentModel;
use api\modules\seller\models\forms\AuthItemChildModel;
use api\modules\seller\models\forms\AuthRoleModel;
use api\modules\seller\models\forms\AuthRoleQuery;
use fanyou\tools\ArrayHelper;
use fanyou\tools\helpers\TreeHelper;
use fanyou\models\base\SearchModel;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */
class AuthRoleService
{

/*********************AuthRole模块服务层************************************/
	/**
	 * 添加一条公用_角色表
	 * @param AuthRoleModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addAuthRole(AuthRoleModel $model)
    {
        $pid = $model->pid;
        if (!empty($pid)) {
            $parentModel= AuthRoleModel::findOne($pid);
            $model->tree = $parentModel->tree . TreeHelper::prefixTreeKey($pid);
        }else{
            $model->tree =TreeHelper::prefixTreeKey(0);
        }
	    $model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param AuthRoleQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getAuthRolePage(AuthRoleQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => AuthRoleModel::class,
			'scenario' => 'default',
			'partialMatchAttributes' => ['name'], // 模糊查询
			'defaultOrder' => [
			'created_at' => SORT_DESC
			],
			'pageSize' => $query->limit,
		]);
		$searchWord = $searchModel->search(array_filter($query->toArray()));
		$result['list'] = ArrayHelper::toArray($searchWord->getModels());
		$result['totalCount'] = $searchWord->pagination->totalCount;
		return $result;
	}
    public function getAuthRoleList(AuthRoleQuery $query): array
    {
        $searchModel = new SearchModel([
            'model' => AuthRoleModel::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'sort' => SORT_DESC
            ],
        ]);
        $searchWord = $searchModel->search(array_filter($query->toArray()));
        return ArrayHelper::toArray($searchWord->getModels());
    }
	/**
	 * 根据Id获取角色表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return AuthRoleModel::findOne($id);
	}

	/**
	 * 根据Id更新角色表
	 * @param AuthRoleModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateAuthRoleById (AuthRoleModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除角色表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
	    $model = AuthRoleModel::findOne($id);

        AuthItemChildModel::deleteAll(['role_id' => $id]);
        AuthAssignmentModel::deleteAll(['role_id' =>$id]);
	    AuthRoleModel::deleteAll(['like', 'tree', $model->tree . TreeHelper::prefixTreeKey($id) . '%', false]);

		return  $model->delete();
	}




}
/**********************End Of AuthRole 服务层************************************/

