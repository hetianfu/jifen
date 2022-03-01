<?php

namespace api\modules\seller\service;

use api\modules\seller\models\forms\AuthItemChildModel;
use api\modules\seller\models\forms\AuthItemChildQuery;
use api\modules\seller\models\forms\AuthItemModel;
use fanyou\enums\AppEnum;
use fanyou\enums\entity\WhetherEnum;
use fanyou\models\base\SearchModel;
use fanyou\tools\ArrayHelper;
use fanyou\tools\helpers\TreeHelper;
use Yii;
use yii\web\UnprocessableEntityHttpException;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-03-25
 */
class AuthItemChildService
{
    /**
     * 当前的角色所有权限
     *
     * @var array
     */
    protected $allAuthNames = [];
/*********************AuthItemChild模块服务层************************************/
	/**
	 * 添加一条授权角色权限表
	 * @param AuthItemChildModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addAuthItemChild(AuthItemChildModel $model)
	{
	    $model->insert();
		return $model->getPrimaryKey();
	}
	/**
	 * 分页获取列表
	 * @param AuthItemChildQuery $query
	 * @return array
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function getAuthItemChildPage(AuthItemChildQuery $query): array
	{
		$searchModel = new SearchModel([
			'model' => AuthItemChildModel::class,
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
	 * 根据Id获取授权角色权限表
	 * @param $id
	 * @return Object
	 */
	public function getOneById($id)
	{
		return AuthItemChildModel::findOne($id);
	}

	/**
	 * 根据Id更新授权角色权限表
	 * @param AuthItemChildModel $model
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function updateAuthItemChildById (AuthItemChildModel $model): int
	{
		$model->setOldAttribute('id',$model->id);
		return $model->update();
	}
	/**
	 * 根据Id删除授权角色权限表
	 * @param $id
	 * @return int
	 * @throws \Throwable
	 * @throws \yii\db\StaleObjectException
	 */
	public function deleteById ($id) : int
	{
		$model = AuthItemChildModel::findOne($id);
		return  $model->delete();
	}




    /**
     * 获取用户所有的权限 - 包含插件
     *
     * @param $role
     * @return array
     */
    public function getAuthByRole($role, $app_id, $addons_name = '')
    {
        if (!empty($this->allAuthNames)) {
            return $this->allAuthNames;
        }

        // 获取当前角色的权限
        $allAuth = AuthItemChildModel::find()
            ->select(['addons_name', 'name'])
            ->where(['role_id' => $role['id']])
            ->andWhere(['app_id' => $app_id])
            ->andFilterWhere(['addons_name' => $addons_name])
            ->asArray()
            ->all();

        $addonsName = [];
        foreach ($allAuth as $item) {
            !isset($addonsName[$item['addons_name']]) && $this->allAuthNames[] = $item['addons_name'];

            $this->allAuthNames[] = $item['name'];
            $addonsName[$item['addons_name']] = true;
        }

        return $this->allAuthNames;
    }

    /**
     * @param $allAuthItem
     * @param $allMenu
     * @param $name
     * @throws UnprocessableEntityHttpException
     * @throws \yii\db\Exception
     */
    public function accreditByAddon($allAuthItem, $allMenu, $removeAppIds, $name)
    {
        // 卸载权限
        Yii::$app->services->rbacAuthItem->delByAddonsName($name);
        // 重组
        foreach ($allAuthItem as &$val) {
            $val = ArrayHelper::regroupMapToArr($val, 'name');
        }

        $defaultAuth = [];
        // 默认后台权限
        if (!empty($allAuthItem[AppEnum::BACKEND])) {
            $defaultAuth = ArrayHelper::merge(AddonDefaultRouteEnum::route(AppEnum::BACKEND, $name), $defaultAuth);
        }

        // 默认商家权限
        if (!empty($allAuthItem[AppEnum::MERCHANT])) {
            $defaultAuth = ArrayHelper::merge(AddonDefaultRouteEnum::route(AppEnum::MERCHANT, $name), $defaultAuth);
        }

        // 重组路由
        $allAuth = [];
        foreach ($allAuthItem as $key => $item) {
            if (isset($allMenu[$key])) {
                $menu = ArrayHelper::regroupMapToArr($allMenu[$key]);
                $menu = ArrayHelper::getColumn(ArrayHelper::getRowsByItemsMerge($menu, 'child'), 'route');
            }

            // 菜单类型
            $is_menu = in_array($key, $removeAppIds) ? AuthMenuEnum::TOP : AuthMenuEnum::LEFT;
            $allAuth = ArrayHelper::merge($allAuth, $this->regroupByAddonsData($item, $menu, $is_menu, $name, $key));
        }

        // 创建权限
        $rows = $this->createByAddonsData(ArrayHelper::merge($defaultAuth, $allAuth));
        // 批量写入数据
        $field = ['title', 'name', 'app_id', 'is_addon', 'addons_name', 'pid', 'level', 'sort', 'tree', 'created_at', 'updated_at'];
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItem::tableName(), $field, $rows)->execute();

        unset($data, $allAuth, $installData, $defaultAuth);
    }

    /**
     * 根据角色信息去授权
     * @param $id
     * @param $itemChilds
     * @return int|void
     * @throws \yii\db\Exception
     */
    public function accreditByDefault($id,  $itemChilds)
    {
        // 删除原先所有权限
        $count=AuthItemChildModel::deleteAll(['role_id' => $id]);
        if (empty($itemChilds)) {
            return $count;
        }

        $rows = [];
        foreach ($itemChilds as $child) {
            $rows[] = [
                $id,
                $child['id'],
                $child['name'],
                $child['appId'] ??'',
                $child['isMenu'],
               // $child['addons_name'] ??'',
            ];
        }
        $field = ['role_id', 'item_id', 'name', 'app_id', 'is_menu'];//, 'addons_name'
        !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItemChildModel::tableName(), $field, $rows)->execute();
        return count($rows);
    }

    /**
     * 获取某角色的所有权限
     *
     * @param $role_id
     * @return array
     */
    public function findItemByRoleId($role_id)
    {
        $auth = AuthItemChildModel::find()
            ->where(['role_id' => $role_id])
            ->with(['item'])
            ->asArray()
            ->all();

        return array_column($auth, 'item');
    }
    /**
     * 获取某角色的所有权限
     *
     * @param array $role_ids
     * @return array
     */
    public function findItemByRoleIds(array $role_ids)
    {

        $auth = AuthItemChildModel::find()
            ->select([ 'item_id'])
            ->distinct()
            ->where(['in','role_id' ,$role_ids])
            //->with(['item'])
            ->asArray()
            ->all();
        return array_column($auth, 'item_id');
    }

    /**
     * @param $item
     * @param $menu
     * @param $name
     * @param $app_id
     * @return array
     */
    protected function regroupByAddonsData($item, $menu, $is_menu, $name, $app_id)
    {
        foreach ($item as &$value) {
            $value['app_id'] = $app_id;
            $value['is_addon'] = WhetherEnum::ENABLED;
            $value['addons_name'] = $name;

            // 组合子级
            if (isset($value['child']) && !empty($value['child'])) {
                $value['child'] = $this->regroupByAddonsData($value['child'], $menu, $is_menu, $name, $app_id);
            }
        }

        return $item;
    }

    /**
     * @param array $data
     * @param int $pid
     * @param int $level
     * @param string $parent
     * @return array
     * @throws UnprocessableEntityHttpException
     */
    protected function createByAddonsData(array $data, $pid = 0, $level = 1, $parent = '')
    {
        $rows = [];

        foreach ($data as $datum) {
            $model = new AuthItemModel();
            $model = $model->loadDefaultValues();
            $model->attributes = $datum;
            // 增加父级
            !empty($parent) && $model->setParent($parent);
            $model->pid = $pid;
            $model->level = $level;
            $model->name = '/' . StringHelper::toUnderScore($model->addons_name) . '/' . $model->name;
            $model->setScenario('addonsBatchCreate');
            if (!$model->validate()) {
                throw new UnprocessableEntityHttpException($this->getError($model));
            }

            // 创建子权限
            if (isset($datum['child']) && !empty($datum['child'])) {
                // 有子权限的直接写入
                if (!$model->save()) {
                    throw new UnprocessableEntityHttpException($this->getError($model));
                }

                $rows = array_merge($rows, $this->createByAddonsData($datum['child'], $model->id, $level++, $model));
            } else {
                $model->tree = !empty($parent) ?  $parent->tree . TreeHelper::prefixTreeKey($parent->id) : TreeHelper::defaultTreeKey();

                $rows[] = [
                    $model->title,
                    $model->name,
                    $model->app_id,
                    $model->is_addon,
                    $model->addons_name,
                    $pid,
                    $level,
                    $model->sort ?? 9999,
                    $model->tree,
                    time(),
                    time(),
                ];

                unset($model);
            }
        }

        return $rows;
    }
}
/**********************End Of AuthItemChild 服务层************************************/

