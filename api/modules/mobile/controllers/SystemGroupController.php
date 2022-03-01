<?php

namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\BaseQuery;
use api\modules\mobile\models\forms\SystemGroupQuery;
use api\modules\mobile\service\SystemConfigService;
use api\modules\mobile\service\SystemGroupService;
use common\utils\FuncHelper;
use fanyou\enums\SystemConfigEnum;
use fanyou\service\FanYouSystemGroupService;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class SystemGroupController
 * @package api\modules\mobile\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-02-19 8:49
 */
class SystemGroupController extends BaseController
{
    private $service;
    private $configService;

    public function init()
    {
        parent::init();
        $this->service = new SystemGroupService();
        $this->configService = new SystemConfigService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['get-index-detail-by-id', 'get-index-data', 'get-index-detail-page', 'get-index-app-config', 'get-common-config', 'get-common-son-config']
        ];
        return $behaviors;
    }
    /*********************SystemGroup模块控制层************************************/

    /**
     * 分页获取所有列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetList()
    {
        $query = new SystemGroupQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getSystemGroupList($query);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }

    /**
     * 获取首页参数
     * @return mixed
     */
    public function actionGetIndexData()
    {
        $result = [];
        $array = $this->configService->findConfigValueUnique(SystemConfigEnum::INDEX_PAGE);
        foreach ($array as $k => $v) {
            $groupInfo = $this->service->getOneById($v);
            if (!empty($groupInfo)) {
                !isset($groupInfo['limit_number']) && $groupInfo['limit_number'] = 10;
                $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false, 1, $groupInfo['limit_number']);
                if (!empty($gValues)) {
                    isset($groupInfo['spreads']) && $gValues['spreads'] = StringHelper::toCamelize(ArrayHelper::toArray(json_decode($groupInfo['spreads'])));
                    unset($gValues['fields']);
                    $result[] = $gValues;
                }
            }
        }
        return $result;

    }

    /**
     * 获取通用配置
     * @return mixed
     */
    public function actionGetCommonConfig()
    {
        $array = $this->configService->findAllConfigList(explode(",", parent::getRequestId('title')));
        $itme = [];
        if (count($array)) {
            foreach ($array as $k => $v) {
                if ($v['type'] == 'group') {
                    $groupInfo = $this->service->getOneById($v['value']);
                    $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo);
                    if (!empty($gValues)) {
                        unset($gValues['fields']);
                    }
                    $itme[$k] = $gValues;
                } else {
                    $itme[$k] = $v;
                }
            }
        }
        return $itme;

    }

    /**
     * 获取通用子配置
     * @return mixed
     */
    public function actionGetCommonSonConfig()
    {
        $son = parent::getRequestId('son');
        $title= parent::getRequestId('title');
        //加入缓存
        $tokenId =SystemConfigEnum::REDIS_COMMON_CONFIG.$title.$son;
        $tokenContent=Yii::$app->cache->get($tokenId);
        if(!empty($tokenContent)){
            return json_decode($tokenContent) ;
        }
        $result = [];
        $array = $this->configService->findConfigList(parent::getRequestId('title'));
        foreach ($array as $k => $v) {
            if ($v['menu_name'] == $son) {
                if ($v['type'] == 'group') {
                    $groupInfo = $this->service->getOneById($v['value']);
                    $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false);
                    if (!empty($gValues)) {
                        unset($gValues['fields']);
                    }
                    $result[$v['menu_name']] = $gValues;
                } else {
                    $result[$v['menu_name']] = $v;
                }
            }
        }
        Yii::$app->cache->set($tokenId, json_encode(StringHelper::toCamelize($result)), 600);

        return $result;
    }

    /**
     * 获取通用子配置
     * @return mixed
     */
    public function actionGetCommonSonConfigList()
    {
        $son = parent::getRequestId('son');
        $result = [];
        $array = $this->configService->findAllConfigList(explode(",", parent::getRequestId('title')));
        foreach ($array as $k => $v) {

            if ($v['menu_name'] == $son) {
                if ($v['type'] == 'group') {
                    $groupInfo = $this->service->getOneById($v['value']);
                    $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false);
                    if (!empty($gValues)) {
                        unset($gValues['fields']);
                    }
                    $result[$v['menu_name']] = $gValues;
                } else {

                    $result[$v['menu_name']] = $v;
                }

            }

        }
        return $result;
    }


    /**
     * 小程序参数配置
     * @return mixed
     */
    public function actionGetIndexAppConfig()
    {
        $tokenId = "html_index_app_config";
        $tokenContent = Yii::$app->cache->get($tokenId);
//TODO
//        if (!empty($tokenContent)) {
//            return json_decode($tokenContent);
//        }

        $result = [];
        $array = $this->configService->findConfigList(SystemConfigEnum::INDEX_APP_CONFIG);
        foreach ($array as $k => $v) {
            if ($v['type'] == 'group') {
                $groupInfo = $this->service->getOneById($v['value']);
                $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo);
                if (!empty($gValues)) {
                    unset($gValues['fields']);
                }
                $result[$v['menu_name']] = StringHelper::toCamelize($gValues);
            } else {
                $result[$v['menu_name']] =StringHelper::toCamelize( $v);
            }
        }
        Yii::$app->cache->set($tokenId, json_encode($result), 600);
        return $result;

    }

    /**
     * 获取首页参数详情
     * @return mixed
     */
    public function actionGetIndexDetailById()
    {
        $groupInfo = $this->service->getOneById(parent::getRequestId());
        unset($groupInfo['fields']);
        return $groupInfo;
    }

    /**
     * 更多
     * @return array
     */
    public function actionGetIndexDetailPage()
    {
        $result['totalCount'] = 0;
        $query = new BaseQuery();
        $query->setAttributes(parent::getRequestGet(false));
        $groupInfo = $this->service->getOneById($query->id);
        if (!empty($groupInfo)) {
            $gValues = FanYouSystemGroupService::getSystemGroupDate($groupInfo, false, $query->page, $query->limit);
            if (!empty($gValues)) {
                isset($groupInfo['spreads']) && $gValues['spreads'] = StringHelper::toCamelize(ArrayHelper::toArray(json_decode($groupInfo['spreads'])));
                unset($gValues['fields']);
                $result['list'] = $gValues['items'];
                $result['totalCount'] = $gValues['totalCount'];
            }
            return $result;
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     */
    public function actionGetOneById()
    {
        return $this->service->getSystemGroupById(parent::getRequestId());
    }


}
/**********************End Of SystemGroup 控制层************************************/ 


