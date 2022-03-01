<?php
//namespace fanyou\components\printDrive;
//
//use api\modules\mobile\models\forms\UserShareModel;
//use api\modules\mobile\service\WechatService;
//use fanyou\common\ShareProduct;
//use fanyou\components\PhotoMerge;
//use fanyou\enums\entity\ShareTypeEnum;
//use fanyou\enums\HttpErrorEnum;
//use fanyou\error\errorCode\ErrorProduct;
//use fanyou\error\FanYouHttpException;
//use fanyou\models\common\Attachment;
//use fanyou\tools\UploadHelper;
//use Yii;
//
///**
// * 生成用户分享图
// * Class ShareUserImg
// * @package fanyou\components\printDrive
// * @author: Administrator
// * @E-mail: admin@163.com
// * @date: 2020-07-04 10:32
// */
//class ShareUserImg extends ShareImgInterface
//{
//    private $md5Id ;
//    private $userId ;
//    private $keyId ;
//    private $shareType=ShareTypeEnum::USER;
//    public function __construct($id)
//    {
//        parent::__construct();
//        $this->userId=$id;
//        $this->keyId=$id;
//        $this->md5Id=md5($id.$id.$this->shareType);;
//    }
//
//    /**
//     * 初始化
//     */
//    protected function create(){
//        //用户
//        $shareInfo=new ShareProduct();
//        $shareUrl= $this->shareService->getShareUrl($this->md5Id);
//        if($shareUrl){
//            return $shareUrl;
//        }
//        $miniAppService=new WechatService();
//        $appCodePath=$miniAppService->createMiniAppCode($this->md5Id,$shareInfo->page_path);
//        //,$shareInfo->scene,$shareInfo->page);
//
//        //合成之后的图片需要存入阿里云，下次进来的时候，如何获取地址，再次定位
//        $config = array(
//            'image'=>array(
//                $this->miniAppCodeArray($appCodePath),
//            ),
//            'background'=>Yii::getAlias('@attachment').'/share-user.jpg',
//        );
//        $filename=$this->md5Id.'.jpg';
//
//        $filePath =  sys_get_temp_dir() . '/'.$filename;
//
//        if(! PhotoMerge::createPoster($config,$filePath)){
//            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorProduct::SYSTEM_ERROR);
//        }
//
//        $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
//        $array=$upload->save(Yii::$app->params['userImage']['share'],$filePath,$filename );
//
//        $shareUrl=$array['accessUrl'];
//        $model=new UserShareModel();
//        $model->id=$this->md5Id;
//        $model->user_id=$this->userId;
//        $model->key_type=$this->shareType;
//        $model->key_id=$this->keyId;
//        $model->share_url=$shareUrl;
//        $this->shareService->addUserShare( $model);
//        return $shareUrl;
//    }
////    private function  getShareImg($appCodePath):array{
////        return $shareUrl;
////    }
//    /**
//     * 二维码资源位置
//     * @param $appCodePath
//     * @return array
//     */
//    private function  miniAppCodeArray($appCodePath):array{
//        return  array(
//            'url'=>  $appCodePath,
//            'stream'=>0,
//            'left'=>150,
//            'top'=>770,
//            'right'=>0,
//            'bottom'=>0,
//            'width'=>160,
//            'height'=>160,
//            'opacity'=>100
//        ) ;
//    }
//    /**
//     * 头像位置
//     * @param $headImgPath
//     * @return array
//     */
//    private function  headImgArray($headImgPath):array{
//        return array(
//            'url'=>  $headImgPath   ,
//            'stream'=>0,
//            'left'=>180,
//            'top'=>750,
//            'right'=>0,
//            'bottom'=>0,
//            'width'=>132,
//            'height'=>132,
//            'opacity'=>132
//        );
//    }
//}
//
