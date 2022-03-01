<?php
namespace api\modules\mobile\models\forms;

use fanyou\service\UserHostIp;
use Yii;
use yii\base\Model;

/**
 * @property  attach
 */
class WxPayRequest extends Model{

    public $out_trade_no;
    public $total_fee;
    public $body;

    public $openid;
    //H5支付的交易类型为MWEB
    public $trade_type;

    public $notify_url;
    public $attach;
    public $scene_info;
    //必须传正确用户端Ip
    public $spbill_create_ip;
    //WAP网站应用
    //    {"h5_info": //h5支付固定传"h5_info"
    //    {"type": "",  //场景类型
    //    "wap_url": "",//WAP网站URL地址
    //    "wap_name": ""  //WAP 网站名
    //    }
    //    }
    //{"h5_info": {"type":"Wap","wap_url": "https://pay.qq.com","wap_name": "腾讯充值"}}
    public function __construct($openid,$tradeType='JSAPI',$body='购买商品')
    {
       $this->openid=$openid;
       $this->body=$body;
       $this->trade_type=$tradeType;
       if($this->trade_type=='MWEB'){
           $this->scene_info=json_encode(['h5_info'=>['type'=>'Wap', "wap_url"=>Yii::$app->request->getHostInfo().Yii::$app->request->url,"wap_name"=>'微小商城' ]]);
           $this->spbill_create_ip= UserHostIp::getIP();//  '171.213.77.34';//
           $this->openid=null;
       }
    }


}

