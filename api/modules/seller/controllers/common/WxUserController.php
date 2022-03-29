<?php

namespace api\modules\seller\controllers\common;

use api\modules\seller\controllers\BaseController;
use api\modules\seller\models\wechat\Menu;
use api\modules\seller\service\wechat\MenuService;
use common\helpers\ResultHelper;
use Yii;

/**
 * 微信用户
 *
 * Class MenuController
 * @package addons\Wechat\merchant\controllers
 * @author jianyuan <admin@163.com>
 */
class WxUserController extends BaseController
{
    private $service;
    public function init()
    {
        parent::init();
        $this->service = new MenuService();

    }

    /**
     * 拉黑用户
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSetWxUserBlack()
    {
        return Yii::$app->wechat->app->user->block( Yii::$app->request->post("openIds"));
    }
    public function actionGetWxUserBlackList()
    {
        return Yii::$app->wechat->app->user->blacklist( );
    }

    /**
     *  取消拉黑用户
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionSetWxUserWhite()
    {

        return Yii::$app->wechat->app->user->unblock( Yii::$app->request->post("openIds"));
    }

}