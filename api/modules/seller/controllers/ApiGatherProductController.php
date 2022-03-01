<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use api\modules\seller\models\forms\ProductModel;
use api\modules\seller\models\forms\ProductSkuModel;
use api\modules\seller\service\AppVersionService;
use fanyou\components\SystemConfig;
use fanyou\error\FanYouHttpException;
use fanyou\tools\ArrayHelper;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Yii;

/**
 * Class AppVersionController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class ApiGatherProductController extends BaseController
{

    private $configService;

    public function init()
    {
        parent::init();
        $this->configService = new SystemConfig();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
            'optional' => ['gather-product']
        ];
        return $behaviors;
    }

    /*********************AppVersion模块控制层************************************/

    static function actionGatherProduct()
    {
        $tokenId = 'temp-gather-product';
        $tokenContent = Yii::$app->cache->get($tokenId);
        if (empty($tokenContent)) {

            $url = 'https://kltn.api.shop.tickscloud.com/v1/product/temp';
            $client = new Client();
            try {
                $response = $client->get($url, [
                    RequestOptions::HEADERS => [
                        'x-weimiao-mini-app-id' => 'wx22343eaeb0a18270',
                        'x-weimiao-tenantid' => '79VbzD0mAQ0LqBJ8']
                ]);

                $body = $response->getBody()->getContents();
                $e = ArrayHelper::toArray(json_decode($body));
                if ($e['code'] != 200) {
                    throw new FanYouHttpException($e['msg']);
                }
            } catch (\Exception $e) {
                $r['list'] = [];
                $r['total'] = 0;
                return $r;
            }
            $list = $e['data'];
            $tokenContent = json_encode($list);
            Yii::$app->cache->set($tokenId, $tokenContent, 7200);

        } else {
            $list = json_decode($tokenContent);
        }
        $list = [$list[0]];
        foreach ($list as $k => $value) {
            $p = new ProductModel();
            $p->type='REAL';
            $p->id = $value['id'];
            $p->name = $value['name'];
            $p->is_hot = $value['hot'];
            $p->sub_title = $value['subTitle'];

            $p->sale_price = $value['price']/100;
            $p->origin_price = $value['marketingPrice']/100;
            $p->member_price = $value['grouponSinglePrice']/100;
            $p->cost_price = $value['price']/100;

            $p->product_score = $value['useCredits'];
            $p->unit_snap = json_encode(['name' => $value['quantityUnit']]);


            $p->cover_img = $value['mainImage'];
            $p->images = $value['album'];
            $p->description = $value['description'];

            $p->volume = $value['volume'];
            $p->base_sales_number = $value['baseCommentNum'];
            $p->stock_number = $value['stock'];
            $skuList = [];
            $skus = $value['skus'];

            foreach ($skus as $m => $v) {
                $sku = new ProductSkuModel();
                $sku->id = $v['id'];
                $sku->sale_price = $v['price']/100;
                $sku->origin_price = $v['marketingPrice']/100;
                $sku->stock_number = $v['stock'];
                $sku->images = $v['image'];
                $sku->stock_number = $v['stock'];
                $sku->bar_code = $v['skuCode'];

                $sku->spec_snap = json_encode($v['specificationsArray']);
                // $sku->product_score= $value['useCredits'] ;//$v['maxUserCrmPoints'];
                $skuList[] = $sku;
            }
            $p->sku_list = $skuList;

        }

        return $e['data'];
    }

}
/**********************End Of AppVersion 控制层************************************/ 


