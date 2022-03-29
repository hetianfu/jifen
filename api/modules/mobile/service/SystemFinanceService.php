<?php

namespace api\modules\mobile\service;

use api\modules\mobile\models\forms\SystemFinanceModel;

/**
 * Class SystemFinanceService
 * @package api\modules\seller\service
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-16
 */
class SystemFinanceService
{

/*********************SystemFinance模块服务层************************************/
	/**
	 * 添加一条资金监控
	 * @param SystemFinanceModel $model
     * @return mixed
     * @throws \Throwable
     */
	public function addSystemFinance(SystemFinanceModel $model)
	{
		$model->insert();
		return $model->getPrimaryKey();
	}

}
/**********************End Of SystemFinance 服务层************************************/

