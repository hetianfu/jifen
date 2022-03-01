<?php

namespace api\modules\seller\service\wechat;

use Yii;

/**
 * @author E-mail: Administrator@qq.com
 * Create Time: 2020-05-21
 */
class WechatSummaryService
{


    /*********************模块服务层************************************/
    /**
     * 获取小程序概况趋势
     * @param $from
     * @param $to
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function summaryTrend($from,$to)
    {
         return   Yii::$app->wechat->miniProgram->data_cube->summaryTrend($from,$to);
    }

    public function dailyVisitTrend($from,$to)
    {
        return   Yii::$app->wechat->miniProgram->data_cube->dailyVisitTrend($from,$to);
    }
    }
/**********************End Of WechatMessage 服务层************************************/

