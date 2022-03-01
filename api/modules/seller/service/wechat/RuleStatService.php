<?php

namespace api\modules\seller\service\wechat;

use api\modules\seller\models\wechat\RuleStat;

/**
 * Class RuleStatService
 * @package api\modules\seller\service\wechat
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-09 11:36
 */
class RuleStatService
{
    /**
     * 插入今日规则统计
     *
     * @param $rule_id
     */
    public function set($rule_id)
    {
        $ruleStat = RuleStat::find()
            ->where(['rule_id' => $rule_id, 'created_at' => strtotime(date('Y-m-d'))])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();

        if ($ruleStat) {
            $ruleStat->hit += 1;
        } else {
            $ruleStat = new RuleStat();
            $ruleStat->rule_id = $rule_id;
        }

        $ruleStat->save();
    }
}