<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\SystemConfigTabModel;
use api\modules\mobile\models\forms\SystemConfigTabValueModel;
use fanyou\enums\SortEnum;
use fanyou\enums\StatusEnum;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-04-28
 */
class SystemConfigService extends BasicService
{

    /*********************SystemConfigTab模块服务层************************************/
    /**
     * 提取配置值
     * @param $title
     * @return array|false|void
     */
    public function findConfigList($title): array
    {
        $configTab = SystemConfigTabModel::find()->select(['id'])->where(['eng_title' => $title])->asArray()->one();
        if ($configTab) {
            $configTabValue = SystemConfigTabValueModel::find()
                ->select(['value', 'menu_name', 'type'])
                ->where(['config_tab_id' => $configTab['id'], 'status' => StatusEnum::ENABLED])
                ->orderBy([SortEnum::SHOW_ORDER => SORT_ASC, SortEnum::CREATED_AT => SORT_DESC])
                ->asArray()
                ->all();
            // $values=array_column($configTabValue,'value') ;
            return $configTabValue;
        }
        return [];
    }

    public function findAllConfigList($title): array
    {
        $configTabAll = SystemConfigTabModel::find()->select(['id', 'eng_title'])->where(['in', 'eng_title', $title])->asArray()->all();
        if (count($configTabAll)) {
            $result = [];

            foreach ($configTabAll as $k => $configTab) {
                $configTabValue = SystemConfigTabValueModel::find()
                    ->select(['value', 'menu_name', 'type'])
                    ->where(['config_tab_id' => $configTab['id'], 'status' => StatusEnum::ENABLED])
                    ->orderBy([SortEnum::SHOW_ORDER => SORT_ASC, SortEnum::CREATED_AT => SORT_DESC])
                    ->asArray()
                    ->all();

                if (count($configTabValue)) {
                    $item = [];
                    foreach ($configTabValue as $d => $v) {

                        $item[$v['menu_name']] = $v['value'];
                    }
                    $result [$configTab['eng_title']] = $item;
                }
            }
            return $result;
        }
        return [];
    }

    /**
     * 提取配置值
     * @param $title
     * @return array|false|void
     */
    public function findConfigValueUnique($title): array
    {
        $configTab = SystemConfigTabModel::find()->select(['id'])->where(['eng_title' => $title])->asArray()->one();
        if ($configTab) {
            $configTabValue = SystemConfigTabValueModel::find()
                ->select(['value'])
                ->where(['config_tab_id' => $configTab['id'], 'status' => StatusEnum::ENABLED])
                ->orderBy([SortEnum::SHOW_ORDER => SORT_ASC, SortEnum::CREATED_AT => SORT_DESC])
                ->asArray()
                ->all();
            $values = array_column($configTabValue, 'value');
            return $values;
        }
        return [];
    }

}
/**********************End Of SystemConfig 服务层************************************/

