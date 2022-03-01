<?php

namespace api\modules\seller\controllers\common;

use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\wechat\Menu;
use api\modules\seller\service\wechat\MenuService;
use fanyou\tools\helpers\ResultHelper;
use Yii;


//TODO
/**
 * 查看我的购物车
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class OrdersController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();
        $this->service = new MenuService();

    }

    /**
     * 取 微信菜单 列表
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function actionGetShopCart()
    {
        return ;
    }
    /**
     * 创建菜单
     *
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function actionSaveWxMenu()
    {
        $model = new Menu();
        $model->setAttributes($this->getRequestPost());
        $postList=Yii::$app->request->post('list');
            if (!isset($postList)) {
                return ResultHelper::json(422, '请添加菜单');
            }
            try {
                $this->service->createSave($model, $postList);
                return ResultHelper::json(200, "修改成功");
            } catch (\Exception $e) {
                return ResultHelper::json(422, $e->getMessage());
            }
    }

    /**
     * 根据微信appId获取本地微信菜单
     * @param $id
     * @return mixed
     */
    public function actionGetWxMenuById($id)
    {
        return  $this->service->getOneById($id)  ;
    }
    /**
     * 删除菜单
     * @param $id
     * @return mixed
     */
    public function actionDelById($id)
    {
        return  $this->service->deleteById($id)  ;
    }

    /**
     * 同步菜单
     *
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function actionSyncWxMenu()
    {
        try {
            $this->service->sync();
            return ResultHelper::json(200, '同步菜单成功');
        } catch (\Exception $e) {
            return ResultHelper::json(422, $e->getMessage());
        }
    }


}