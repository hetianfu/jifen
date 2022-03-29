<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\AuthAssignmentModel;
use api\modules\seller\models\forms\AuthAssignmentQuery;
use fanyou\enums\AppEnum;
use fanyou\tools\ArrayHelper;
use fanyou\models\base\SearchModel;
use yii\web\UnprocessableEntityHttpException;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */
class AuthAssignmentService
{

/*********************AuthAssignment模块服务层************************************/
    /**
     * 分配角色
     *
     * @param array $role_ids 角色id
     * @param int $user_id 用户id
     * @param string $app_id 应用id
     * @throws UnprocessableEntityHttpException
     */
    public function addAuthAssignment(array $role_ids, int $user_id, string $app_id=AppEnum::BACKEND)
    {
        // 移除已有的授权
        AuthAssignmentModel::deleteAll(['user_id' => $user_id, 'app_id' => $app_id]);
        if(empty($role_ids)){
            return;
        }
        foreach ($role_ids as $role_id) {
            $model = new AuthAssignmentModel();
            $model->user_id = $user_id;
            $model->role_id = $role_id;
            $model->app_id = $app_id;

            if (!$model->save()) {
                throw new UnprocessableEntityHttpException($model->getFirstError());
            }
        }
    }
	/**
	 * 分页获取列表
	 * @param AuthAssignmentQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getAuthAssignmentPage(AuthAssignmentQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => AuthAssignmentModel::class,
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


    /**
     * 获取当前用户权限的下面的所有用户id
     *
     * @param $app_id
     * @return array
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function getChildIds($app_id)
    {
        if (Yii::$app->services->auth->isSuperAdmin()) {
            return [];
        }

        $childRoles = Yii::$app->services->rbacAuthRole->getChildes($app_id);
        $childRoleIds = ArrayHelper::getColumn($childRoles, 'id');
        if (!$childRoleIds) {
            return [-1];
        }

        $userIds = AuthAssignmentModel::find()
            ->where(['app_id' => $app_id])
            ->andWhere(['in', 'role_id', $childRoleIds])
            ->select('user_id')
            ->asArray()
            ->column();

        return !empty($userIds) ? $userIds : [-1];
    }


    /**
     * 获取所有权限（超级管理员）
     * @param $merchantId
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findAllRoles($merchantId)
    {
        $array=AuthAssignmentModel::find()
            ->select([ 'role_id'])
            ->distinct()
            ->where(['merchant_id'=>$merchantId])
            ->all();
        return array_column($array, 'role_id');
    }
    /**
     * 根据userId获取一条
     * @param $user_id
     * @param $app_id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByUserIdAndAppId($user_id, $app_id=AppEnum::BACKEND)
    {
        return AuthAssignmentModel::find()
            //->where(['app_id' => $app_id])
            ->where(['user_id' => $user_id])
            ->select('role_id')
            ->asArray()
            ->one();
    }

    /**
     * 根据userId获取所有roleId
     * @param $user_id
     * @return array
     */

    public function findAllByUserIdAndAppId($user_id)
    {
        $array=AuthAssignmentModel::findAll(['user_id' => $user_id]);
        return array_column($array, 'role_id');

    }
    /**
     * 根据roleId获取所有userId
     * @param $role_id
     * @return array
     */
    public function findUserIdByRoleIdAndAppId($role_id)
    {
        $array=AuthAssignmentModel::findAll(['role_id' => $role_id]);
        return array_column($array, 'user_id');

    }

    /**
     * 移除员工的角色
     * @param $user_id
     */
    public function removeAuthAssignment($user_id)
    {
        // 移除user对应的所有关联角色
        AuthAssignmentModel::deleteAll(['user_id' => $user_id]);
        if(empty($role_ids)){
            return;
        }

    }


}
/**********************End Of AuthAssignment 服务层************************************/

