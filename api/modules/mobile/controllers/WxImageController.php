<?php
namespace api\modules\mobile\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\UserShareModel;
use api\modules\mobile\service\UserShareService;
use api\modules\mobile\service\WechatService;
use fanyou\common\ShareProduct;
use fanyou\components\PhotoMerge;
use fanyou\components\SystemConfig;
use fanyou\enums\HttpErrorEnum;
use fanyou\enums\SystemConfigEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;

/**
 * 订单支付
 *  @author  Round
 *  @E-mail: Administrator@qq.com
 */
class WxImageController extends BaseController
{

    private $miniAppService;
    private $shareService;
    private $configService;
    public function init()
    {
        parent::init();
        $this->miniAppService=new WechatService();
        $this->shareService=new UserShareService();
        $this->configService = new SystemConfig();
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['user-share-img']
        ];
        return $behaviors;
    }
/*********************UserInfo模块控制层************************************/

public function actionGetAliFont()
{
    return Yii::getAlias('@alifont'). '/Alibaba-PuHuiTi-Bold.ttf'; ;
}

    /**
     * 用户分享
     * @return mixed
     * @throws FanYouHttpException
     * @throws \Throwable
     */
    public function actionUserShareImg()
    {
        $basicConfig = $this->configService->getConfigInfoValue(false, SystemConfigEnum::BASIC_CONFIG);
        $backImg=$basicConfig['share_partner'];
        if(empty($backImg)){
            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,'分享图底版缺失');
        }
       // $backImg=empty($backImg)?Yii::getAlias('@attachment').'/share-user.jpg':$backImg;

        $shareInfo=new ShareProduct();
        $shareInfo->setAttributes($this->getRequestPost(),false);
        $keyType=$shareInfo->key_type;
        $keyId=md5($shareInfo->user_id.$shareInfo->user_id.$keyType);
        $shareUrl= $this->shareService->getShareUrl($keyId);
        if($shareUrl){
            return $shareUrl;
        }

        $appCodePath=$this->miniAppService->createMiniAppCode($keyId,$shareInfo->page_path);//,$shareInfo->scene,$shareInfo->page);
        //合成之后的图片需要存入阿里云，下次进来的时候，如何获取地址，再次定位
        $config = array(
            'text'=>array(
            ),
            'image'=>array(
                $this->miniAppCodeArray($appCodePath),
            ),
            'background'=>$backImg,
        );

        $filename=md5($shareInfo->user_id.$shareInfo->user_id.$keyType).'.jpg';

        $filePath =  sys_get_temp_dir() . '/'.$filename;
        if(! PhotoMerge::createPoster($config,$filePath)){
            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorProduct::SYSTEM_ERROR);
        }
        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
        $array=$upload->save(Yii::$app->params['userImage']['share'],$filePath,$filename );

        $shareUrl=$array['accessUrl'];
        $model=new UserShareModel();
        $model->id=$keyId;
        $model->user_id=$shareInfo->user_id;
        $model->key_type=$keyType;
        $model->key_id=$shareInfo->user_id;
        $model->share_url=$shareUrl;
        $this->shareService->addUserShare( $model);
        return $shareUrl;

    }
    /**
     * 二维码资源位置
     * @param $appCodePath
     * @return array
     */
    private function  miniAppCodeArray($appCodePath):array{
        return  array(
            'url'=>  $appCodePath,
            'stream'=>0,
            'left'=>$_ENV['IMG_USER_LEFT'],
            'top'=>$_ENV['IMG_USER_TOP'],
            'right'=>0,
            'bottom'=>0,
            'width'=>160,
            'height'=>160,
            'opacity'=>100
        ) ;
    }
    /**
     * 头像位置
     * @param $headImgPath
     * @return array
     */
    private function  headImgArray($headImgPath):array{
        return array(
            'url'=>  $headImgPath   ,
            'stream'=>0,
            'left'=>180,
            'top'=>750,
            'right'=>0,
            'bottom'=>0,
            'width'=>132,
            'height'=>132,
            'opacity'=>132
        );
    }

	}
/**********************End Of UserInfo 控制层************************************/ 


