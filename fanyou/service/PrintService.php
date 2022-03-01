<?php

namespace fanyou\service;

use api\modules\seller\models\forms\PrintModel;
use fanyou\components\SystemConfig;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\entity\PrintBrandEnum;
use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\tools\PrintHelper;

/**
 * Class FanYouSystemGroupService
 * @package fanyou\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 10:34
 */
class PrintService
{
    public static function printOrderOld($orderInfo, $force = false)
    {
        $service = new SystemConfig();
        $array = $service->getConfigInfo(false, SystemConfigEnum::PRINT_CONFIG);
        if (!empty($array)) {
            $fields = ArrayColumn::getSystemConfigValue($array);
            //判断打印机是否是下单 打印
            if ($force || $fields[PrintConfigEnum::PRINT_AFTER_PAY]) {

                $modelList = PrintModel::findAll(['merchant_id' => $orderInfo['merchant_id'], 'disable' => 1]);
                if (count($modelList)) {
                    foreach ($modelList as $k => $model) {
                        if (!empty($model) && !empty($model->print_sn)) {
                            $send[PrintConfigEnum::PRINT_SN] = $model->print_sn;
                            $send[PrintConfigEnum::CONTENT] = PrintHelper::getContent($orderInfo, $model->brand);

                            $print = new PrintHelper($send, $model->brand);
                            $print->printContent();
                        }
                    }
                }
                return count($modelList);
            }
        }
    }

    public static function printOrder($orderInfo, $force = false)
    {  $number=0;
        $service = new SystemConfig();
        $array = $service->getConfigInfo(false, SystemConfigEnum::PRINT_CONFIG);
        if (!empty($array)) {
            $fields = ArrayColumn::getSystemConfigValue($array);
            //判断打印机是否是下单 打印
            if ($force || $fields[PrintConfigEnum::PRINT_AFTER_PAY]) {
                $modelList = PrintModel::findAll(['merchant_id' => $orderInfo['merchant_id'],'brand'=>PrintBrandEnum::FEI_E_YUN, 'disable' => 1]);
                if (count($modelList)) {
                    foreach ($modelList as $k => $model) {
                        if (!empty($model) && !empty($model->print_sn)) {
                            $send[PrintConfigEnum::PRINT_SN] = $model->print_sn;
                            $send[PrintConfigEnum::CONTENT] = PrintHelper::getContent($orderInfo, $model->brand,$fields[PrintConfigEnum::PRINT_TITLE]);
                            $print = new PrintHelper($send, $model->brand);
                            $print->printContent();
                        }
                    }
                }
                $number= count($modelList);
            }
        }
        $ylyArray = $service->getConfigInfo(false, SystemConfigEnum::PRINT_YLY_CONFIG);
        if (!empty($ylyArray)) {
            $fields = ArrayColumn::getSystemConfigValue($ylyArray);

            //判断打印机是否是下单 打印
            if ($force || $fields[PrintConfigEnum::PRINT_AFTER_PAY]) {
                $modelList = PrintModel::findAll(['merchant_id' => $orderInfo['merchant_id'],'brand'=>PrintBrandEnum::Y_L_YUN, 'disable' => 1]);
                if (count($modelList)) {
                    foreach ($modelList as $k => $model) {
                        if (!empty($model) && !empty($model->print_sn)) {
                            $send[PrintConfigEnum::PRINT_SN] = $model->print_sn;
                            $send[PrintConfigEnum::CONTENT] = PrintHelper::getContent($orderInfo, $model->brand,$fields[PrintConfigEnum::PRINT_TITLE]);
                            $print = new PrintHelper($send, $model->brand);
                            $print->printContent();
                        }
                    }
                }
                $number+= count($modelList);
            }
        }
return $number;

    }
}