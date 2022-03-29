<?php

namespace api\modules\seller\controllers\common;

use api\modules\auth\ApiAuth;
use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\wechat\Menu;
use api\modules\seller\service\wechat\MenuService;
use fanyou\components\SystemConfig;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use Yii;

/**
 * 自定义菜单
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class WxMenuController extends BaseController
{
    private $service;
    private $configService;
    private $wxAppId;
    public function init()
    {
        parent::init();
        $this->service = new MenuService();
        $this->configService = new SystemConfig();

        $this->wxAppId =$this->service->getWxAppId();;
    }
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
     //       'optional'=>['get-wx-menu-list']
        ];
        return $behaviors;
    }
    public function actionGetWxMenu()
    {
        return   $this->service->getOneById($this->wxAppId);

    }

    /**
     * 同步菜单
     * @return int
     */
    public function actionSynWxMenu()
    {
        try {
            return  $this->service->sync($this->wxAppId);
        } catch (\Exception $e) {
            return StatusEnum::FAIL;
        }
    }

    /**
     * 取微信菜单 列表
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function actionGetWxMenuList()
    {
        $menulist = Yii::$app->wechat->app->menu->list();
        if ($menulist['errcode'] == 46003) {
            return [];
        }
        $result['menuData'] = $menulist['menu']['button'];
        return $result;
    }

    /**
     * 保存菜单
     * @return mixed
     * @throws FanYouHttpException
     */
    public function actionSaveWxMenu()
    {
        $model = new Menu();
        $model->setAttributes($this->getRequestPost());
        $model->id=$this->wxAppId;
        $postList = $model->menu_data;
        if (!isset($postList)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'请添加至少一个菜单');
        }
        try {
            return  $this->service->createSave($model, $postList);
        } catch (\Exception $e) {

        }
    }

    /**
     * 删除菜单
     * @param $id
     * @return mixed
     */
    public function actionDelById($id)
    {
        return $this->service->deleteById($id);
    }


}