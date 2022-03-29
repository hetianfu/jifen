<?php
namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ProductSpecQuery;
use api\modules\seller\service\CategoryTagService;
use Yii;
use yii\web\HttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * CategoryTag
 * @author E-mail: Administrator@qq.com
 *
 */
class ProductSpecController extends BaseController
{

    private $service;
    public function init()
    {
        parent::init();
        $this->service = new CategoryTagService();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['add-spec',]
        ];
        return $behaviors;
    }
/*********************CategoryTag模块控制层************************************/ 
	/**
	 * 添加
     * @return mixed
     */
	public function actionAddSpec()
	{
        $arrays=$this->getRequestPost();
        $tagId= $this->service->addProductSpec('',$arrays);
		return $tagId;

	}
    /**
     * 分页获取列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSpecPage()
    {
        $query = new ProductSpecQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getProductSpecPage( $query);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }
    /**
     * 获取所有列表
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSpecList()
    {
        $query = new ProductSpecQuery();
        $query->setAttributes($this->getRequestGet());
        if ( $query->validate()) {
            return $this->service->getProductSpecList( $query);
        } else {
            throw new  UnprocessableEntityHttpException(parent::getModelError($query));
        }
    }

    /**
     * 获取详情
     * @return mixed
     * @throws HttpException
     */
    public function actionGetSpecById(){
        $id =parent::getRequestId();
        return $this->service->getSpecById($id);
    }

    /**
	 * 根据Id更新
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionUpdateSpecById(){
	    $id=Yii::$app->request->post('id');
	    if(empty($id)){
            throw new  UnprocessableEntityHttpException(parent::getModelError("id为空"));
        }
        $arrays=$this->getRequestPost() ;
		return $this->service->updateSpecById($arrays);

	}

	/**
	 * 根据Id删除
	 * @return mixed
	 * @throws HttpException
	 */
	public function actionDelSpecById(){

		$id = parent::getRequestId();
		return $this->service->deleteSpecById($id);
		}



	}



