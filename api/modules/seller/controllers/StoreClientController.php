<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\StoreClientCategoryModel;
use api\modules\seller\models\forms\StoreClientCategoryQuery;
use api\modules\seller\models\forms\StoreClientModel;
use api\modules\seller\models\forms\StoreClientQuery;
use api\modules\seller\service\StoreClientService;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\models\common\Attachment;
use fanyou\tools\UploadHelper;
use Yii;
use yii\web\HttpException;

/**
 * StoreClient
 * @author E-mail: Administrator@qq.com
 *
 */
class StoreClientController extends BaseController
{

    private $service; 
    public function init()
    {
        parent::init();
        $this->service = new StoreClientService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }


    /*********************StoreClientCategory模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAddCategory()
    {
        $model = new StoreClientCategoryModel();
        $model->setAttributes($this->getRequestPost());
        if ($model->validate()) {
            return $this->service->addStoreClientCategory($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryPage()
    {
        $query = new StoreClientCategoryQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getStoreClientCategoryPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryList()
    {
        $query = new StoreClientCategoryQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            $list=$this->service->getStoreClientCategoryList($query);
            return  empty($list)?[]:$list;
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }

    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetCategoryById()
    {
        return $this->service->getOneById(parent::getRequestId());
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateCategoryById()
    {

        $model = new StoreClientCategoryModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateStoreClientCategoryById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }

    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelCategoryById()
    {

        return $this->service->deleteCategoryById(parent::getRequestId());
    }

    /*********************StoreClient模块控制层************************************/
    /**
     * 添加
     * @return mixed
     * @throws HttpException
     */
    public function actionAdd()
    {
        $suffix=Attachment::UPLOAD_TYPE_IMAGES;
        if(strstr(parent::postRequestId("suffix"), 'video')){
            $suffix=Attachment::UPLOAD_TYPE_VIDEOS;
        }
        $upload = new UploadHelper(Yii::$app->request->post(), $suffix);

        return $upload->save(Yii::$app->params['storeClientImage']['img'],'','',parent::postRequestId("categoryId"));
    }

    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetPage()
    {
        $query = new StoreClientQuery();
        $query->setAttributes($this->getRequestGet());
        if ($query->validate()) {
            return $this->service->getStoreClientPage($query);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($query));
        }
    }


    /**
     * 根据Id获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetById()
    {
        return $this->service->getOneById(parent::getRequestId());
    }

    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionUpdateById()
    {

        $model = new StoreClientModel(['scenario' => 'update']);
        $model->setAttributes($this->getRequestPost(false));
        if ($model->validate()) {
            return $this->service->updateStoreClientById($model);
        } else {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, parent::getModelError($model));
        }
    }
    /**
     * 根据Id更新
     * @return mixed
     * @throws HttpException
     */
    public function actionMoveTo()
    {
        $ids = Yii::$app->request->post('ids');
        $idArray = explode(",", $ids);
        return StoreClientModel::updateAll(['category_id'=> Yii::$app->request->post("categoryId")],['in', 'id',$idArray]);

    }
    /**
     * 根据Id删除
     * @return mixed
     * @throws HttpException
     */
    public function actionDelById()
    {
        $ids = parent::getRequestId();
        $idArray = explode(",", $ids);
        foreach ($idArray as $k => $id) {
            $d = $this->service->getOneById($id);
            $upload = new UploadHelper(Yii::$app->request->post(), Attachment::UPLOAD_TYPE_IMAGES);
            $upload->delete($d['key']);
            $this->service->deleteById($id);
        }
        return count($idArray);
    }
}
/**********************End Of StoreClient 控制层************************************/

