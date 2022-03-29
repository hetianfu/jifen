<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\mobile\models\forms\BasicOrderInfoModel;
use api\modules\mobile\models\forms\ProductModel;
use api\modules\seller\service\ProductReplyService;
use api\modules\seller\models\forms\ProductReplyModel;
use api\modules\seller\models\forms\ProductReplyQuery;
use fanyou\enums\StatusEnum;
use fanyou\error\FanYouHttpException;
use fanyou\enums\HttpErrorEnum;
use fanyou\tools\ArrayHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\base\BaseObject;
use yii\web\HttpException;

/**
 * ProductReply
 * @author E-mail: Administrator@qq.com
 *
 */
class ProductReplyController extends BaseController
{

  private $service;
  public function init()
  {
    parent::init();
    $this->service = new ProductReplyService();
  }

  public function behaviors()
  {
    $behaviors = parent::behaviors();
    $behaviors['authenticator'] = [
      'class' => ApiAuth::class,
    ];
    return $behaviors;
  }
  /*********************ProductReply模块控制层************************************/

  /**
   * 分页获取列表
   * @return mixed
   * @throws HttpException
   */
  public function actionGetPage()
  {
    $query = new ProductReplyQuery();
    $query->setAttributes($this->getRequestGet());
    if ( $query->validate()) {
      $query->is_del = 0;
      return $this->service->getProductReplyPage( $query);
    } else {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($query));
    }
  }


  /**
   * 根据Id获取详情
   * @return mixed
   * @throws HttpException
   */
  public function actionGetById(){
    $id = Yii::$app->request->get('id');
    return $this->service->getOneById($id);
  }




  /**
   * 根据Id更新
   * @return mixed
   * @throws HttpException
   */
  public function actionUpdateById(){

    $model = new ProductReplyModel(['scenario'=>'update']);
    $model->setAttributes($this->getRequestPost(false));
    if ($model->validate()) {
      if(!empty($model->product_id)){
        $p= ProductModel::find()->select(['id','type', 'name', 'images','is_on_sale', 'sale_price','sale_strategy'])->where(['id' => $model->product_id])->asArray()->one();
        $model->product_snap=json_encode($p,JSON_UNESCAPED_UNICODE);
      }
      return $this->service->updateProductReplyById($model);
    } else {
      throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,parent::getModelError($model));
    }
  }

  /**
   * 根据Id删除
   * @return mixed
   */
  public function actionDelById(){
    $model = new ProductReplyModel(['scenario'=>'update']);
    $model->id=parent::getRequestId();
    $model->is_del=StatusEnum::ENABLED;

    return 	 $this->service->updateProductReplyById($model);
  }


  /**
   * 添加
   * @return mixed
   */
  public function actionAdd()
  {

    $user_name = Yii::$app->request->post('user_name');
    $portrait = Yii::$app->request->post('portrait');
    $comment = Yii::$app->request->post('comment');
    $pics = Yii::$app->request->post('pics');
    $product_id = Yii::$app->request->post('product_id');

    $model = new ProductReplyModel();
    $model->user_name = $user_name;
    $model->portrait = $portrait;
    $model->comment = $comment;
    $model->pics = $pics;
    $model->product_id = $product_id;
    $model->status = 1;
    $model->is_recommend = 1;
    $model->save(false);
    return  $model->id;

  }




}
/**********************End Of ProductReply 控制层************************************/




