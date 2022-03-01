<?php

namespace fanyou\components\printDrive;

use api\modules\seller\service\common\SystemConfigService;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\entity\PrintBrandEnum;
use fanyou\enums\entity\PrintConfigEnum;
use fanyou\enums\SystemConfigEnum;

/**
 * Class PrintInterface
 * @package fanyou\components\printDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-10 17:33
 */
abstract class   PrintInterface
{
    public $accountId;
    public $accountKey;
    public $thisLogo;
    public $remark;

    public function __construct($printBrand = PrintBrandEnum::FEI_E_YUN)
    {
        if ($printBrand === PrintBrandEnum::Y_L_YUN) {
            //TODO---要改成YLY的
            $printBrand = SystemConfigEnum::PRINT_YLY_CONFIG;
        }
       else  if ($printBrand === PrintBrandEnum::FEI_E_YUN) {
            //TODO---要改成 FEI_E_YUN 的
            $printBrand = SystemConfigEnum::PRINT_CONFIG;
        }
        $service = new  SystemConfigService();
        $array = $service->findConfigValueUnique($printBrand);
        if (!empty($array)) {
            $fields = ArrayColumn::getSystemConfigValue($array);
            if ($printBrand === PrintBrandEnum::Y_L_YUN) {
                $fields[PrintConfigEnum::PRINT_BRAND] = PrintBrandEnum::Y_L_YUN;
            }
            else if ($printBrand === PrintBrandEnum::FEI_E_YUN) {
                $fields[PrintConfigEnum::PRINT_BRAND] = PrintBrandEnum::FEI_E_YUN;
            }
            $this->accountId = $fields[PrintConfigEnum::ACCOUNT_ID];
            $this->accountKey = $fields[PrintConfigEnum::ACCOUNT_SECRET];

            $this->thisLogo = $fields[PrintConfigEnum::PRINT_LOGO];
            $this->create();
        }
    }

    abstract protected function create();

    abstract protected function addPrint();

    abstract protected function printContent($sn, $content, $times);

    abstract protected function updatePrint();

    abstract protected function delPrint();

    abstract protected function cleanWaitTask();

    abstract protected function checkPrintStatus($sn = '');


    abstract protected function printTest();
}

