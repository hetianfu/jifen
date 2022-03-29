<?php

namespace api\modules\seller\service\wechat;

use api\modules\seller\models\wechat\Menu;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use Yii;
use yii\web\UnprocessableEntityHttpException;


/**
 * Class MenuService
 * @package api\modules\seller\service\wechat
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-04-03 10:51
 */
class MenuService
{
    /**
     * 保存菜单
     * @param Menu $model
     * @param $data
     * @return bool|false|int
     * @throws FanYouHttpException
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function createSave(Menu $model, $data)
    {
        $buttons = [];
        foreach ($data as &$button) {
            $button=ArrayHelper::toArray($button);
            $arr = [];
            // 判断是否有子菜单

            if (!empty($button['sub_button'])) {
               // $arr = $this->mergeButton($button);
                $arr['name'] = $button['name'];
                foreach ($button['sub_button'] as &$sub) {

                    $sub_button = $this->mergeButton($sub);
                 //   print_r($sub_button);exit;
                    $sub_button['name'] = $sub['name'];
                    $sub_button['type'] = $sub['type'];
                    $arr['sub_button'][] = $sub_button;
                }

            } else {

                $arr = $this->mergeButton($button);
                $arr['name'] =$button['name'];
                $arr['type'] = $button['type'];
            }
            $buttons[] = $arr;
        }
        $model->menu_data =json_encode($buttons,JSON_UNESCAPED_UNICODE) ;
        // 判断写入是否成功
        if (!$model->validate()) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,$model->getErrors());
        }
        // 个性化菜单
        if ($model->type == Menu::TYPE_INDIVIDUATION) {
            $matchRule = [
                "tag_id" => $model->tag_id,
                "sex" => $model->sex,
                "country" => "中国",
                "province" => trim($model->province),
                "client_platform_type" => $model->client_platform_type,
                "language" => $model->language,
                "city" => trim($model->city),
            ];

            // 创建自定义菜单
            $menuResult = Yii::$app->wechat->app->menu->create($buttons, $matchRule);
            Yii::$app->systemConfig->getWechatError($menuResult);
            $model->menu_id = $menuResult['menuid'];
        } else {

            // 验证微信报错
            Yii::$app->systemConfig->getWechatError(Yii::$app->wechat->app->menu->create($buttons))  ;
        }
        if(!empty(Menu::findOne(['id' => $model->id ]))){
            $model->setOldAttribute("id",$model->id);
            return  $model->update();
        }else {
            return  $model->insert();
        }
    }

    /**
     *  私有方法
     *  合并前端过来的数据
     * @param array $button
     * @return array
     */
    private function mergeButton(array $button)
    {
        $arr = [];
        if ($button['type'] == 'click') {
            //$arr[Menu::$menuTypes[$button['type']]['meta']] = $button['content'];
           $arr['key'] = $button['key'];
        }elseif ( $button['type'] == 'view') {
            $arr['url'] = $button['url'];//$button[Menu::$menuTypes[$button['type']]['meta']];
        } elseif ($button['type'] == 'miniprogram') {
            $arr['appid'] = $button['appid'];
            $arr['pagepath'] = $button['pagepath'];
            $arr['url'] = $button['url'];
        } else {
            $arr[Menu::$menuTypes[$button['type']]['meta']] = Menu::$menuTypes[$button['type']]['value'];
        }

        return $arr;
    }


    /**
     * 同步微信菜单
     * @param null $id
     * @return bool|false|int
     * @throws UnprocessableEntityHttpException
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function sync($id=null)
    {
        $model = new Menu;
        $model->id=$id;

        // 获取菜单列表
        $list = Yii::$app->wechat->app->menu->list();
        // 解析微信接口是否报错
        Yii::$app->systemConfig->getWechatError($list);

        // 开始获取自定义菜单同步
        if (!empty($list['menu'])) {
            $model->title = "默认菜单";
            $model->menu_data = json_encode($list['menu']['button'], JSON_UNESCAPED_UNICODE);
            $model->menu_id = isset($list['menu']['menuid']) ? $list['menu']['menuid'] : '';
        }
        // 个性化菜单
        if (!empty($list['conditionalmenu'])) {
            foreach ($list['conditionalmenu'] as $menu) {
                if (!($model = Menu::findOne(['menu_id' => $menu['menuid']]))) {
                    $model = new Menu;
                    $model = $model->loadDefaultValues();
                }
                $model->title = "个性化菜单";
                $model->attributes = $menu['matchrule'];
                $model->type = Menu::TYPE_INDIVIDUATION;
                $model->tag_id = isset($menu['group_id']) ? $menu['group_id'] : '';
                $model->menu_data = $menu['button'];
                $model->menu_id = $menu['menuid'];
            }
        }
        if(!empty($model->id)){
            $model->setOldAttribute('id',$id);
            $result=   $model->update();
        }else{
            $result=  $model->insert();
        }
        return $result;
    }
    /**
     * 获取微信appId
     * @return Menu|null
     */
    public function getWxAppId()
    {
        return Yii::$app->wechat->app->getConfig()['app_id'];
    }
    /**
     * 获取微信 菜单
     * @param $id
     * @return Menu|null
     */
    public function getOneById($id)
    {
        return Menu::findOne($id);
    }
    /**
     * 删除菜单
     * @param $id
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    protected function deleteById($id)
    {
        $model = Menu::findOne($id);
        // 个性化菜单删除 : 普通菜单删除
        !empty($model['menu_id']) ?Yii::$app->wechat->app->menu->delete($model['menu_id']): Yii::$app->wechat->app->menu->delete();
        return  $model->delete();
    }

}