<?php
namespace fanyou\components\systemDrive;


use api\modules\mobile\models\forms\SystemConfigTabModel;
use api\modules\seller\service\common\SystemConfigService;
use fanyou\enums\StatusEnum;

/**
 * Class SystemConfigInterface
 * @package fanyou\components\systemDrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-01-20 17:32
 */
abstract class  SystemConfigInterface
{
    protected $configId;
    protected $configService;

    public function __construct($type)
    {
        $model=SystemConfigTabModel::findOne(['eng_title' => $type,'status' => StatusEnum::ENABLED] );

        !empty($model)&&  $this->configId =$model['id'];
        $this->configService = new SystemConfigService();

    }

    abstract protected function getValue();

}

