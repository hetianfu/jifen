<?php

namespace api\modules\seller\controllers;

use api\modules\auth\ApiAuth;
use fanyou\enums\entity\StrategyTypeEnum;
use fanyou\service\GatherProductService;

/**
 * Class AppVersionController
 * @package api\modules\seller\controllers
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-13
 */
class ApiFor99Controller extends BaseController
{

    public function init()
    {
        parent::init();
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => ApiAuth::class,
        ];
        return $behaviors;
    }

    /*********************AppVersion模块控制层************************************/


    public function actionGetDetail()
    {
        $url = parent::getRequestId("url");
        $data = GatherProductService::gatherProduct($url);
        $data['unit_snap'] = json_encode(["name" => $data['unit']], JSON_UNESCAPED_UNICODE);
        $data['unit_id'] = '0';
        $data['sale_strategy'] = StrategyTypeEnum::NORMAL;
        return $data;
    }

//    public function actionGetDetail()
//    {
//         const TM = "tmall.com";
//         const TB = "taobao.com";
//         const JD = "jd.com";
//         const PDD = "yangkeduo.com";
//
//         const SUNING = "product.suning.com";
//         const ALBB = "1688.com";
//        $url = parent::getRequestId("url");
//        $sourceArray = $this->provideSource($url);
//        if (empty($sourceArray)) {
//            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, parent::getModelError("请填入正确的商品连接"));
//        }
//        $baseUrl = $sourceArray['url'];
//        $SOURCE = $sourceArray['source'];
//        $id = $sourceArray['id'];
//        $basicConfig = $this->configService->getConfigInfoValue(true, SystemConfigEnum::API_99_KEY);
//        $apiKey = $basicConfig[SystemConfigEnum::API_99_KEY];
//        if (empty($apiKey)) {
//            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, parent::getModelError("API_KEY未填入"));
//        }
//        $method = "GET";
//        $baseUrl .= "?apikey=" . $apiKey . "&itemid=" . $id;
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
//        curl_setopt($curl, CURLOPT_URL, $baseUrl);
//        curl_setopt($curl, CURLOPT_FAILONERROR, false);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HEADER, true);
//        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//        curl_close($curl);
//        if ($err) {
//            throw new FanYouHttpException(HttpErrorEnum::Expectation_Failed, parent::getModelError("解析遇到异常，请查验来源" . $err));
//
//        } else {
//            list($header, $body) = explode("\r\n\r\n", $response, 2);
//            $jsonArray = ArrayHelper::toArray(json_decode($body));
//            $data = $this->dataToProduct($SOURCE, $jsonArray['data']);
//            $data['get_from'] = $SOURCE;
//            return $data;
//        }
//    }
//
//    public function dataToProduct($SOURCE, $data)
//    {
//        $result = '';
//        switch ($SOURCE) {
//            case self::TM:
//            case self::TB:
//                $result = $this->dataToProductFromTm($data['item']);
//                break;
//            case self::JD:
//                $result = $this->dataToProductFromJd($data['item']);
//                break;
//            case self::PDD:
//                $result = $this->dataToProductFromPDD($data['item']);
//                break;
//            case self::ALBB:
//                $result = $this->dataToProductFromAlbb($data);
//                break;
//            case self::SUNING:
//                $result = $this->dataToProductFromSN($data);
//                break;
//
//        }
//
//        return $result;
//    }


//    public function dataToProductFromTm($data)
//    {
//        $product = new ProductModel();
//        $product->is_sku = 0;
//
//        $product->images = json_encode($data['images']);
//        $product->cover_img = $data['images'][0];
//
//        $product->thumb_count = $data['favouriteCount'];
//
//
//        $product->name = $data['title'];
//        $product->sub_title = $data['subTitle'];
//        if (!empty($data["videos"])) {
//            $product->video = $data["videos"][0]['url'];
//        }
//        //  $product->product_spec = json_encode($data['groupProps'], JSON_UNESCAPED_UNICODE);
//        $price = $data['priceRange'];
//        if (empty($price)) {
//            $price = $data['price'];
//        }
//        $price = substr($price, 0, strrpos($price, "-"));
//        $product->cost_price = $price;
//        $product->sale_price = $price;
//        $product->member_price = $price;
//        $product->origin_price = $price;
//
//        $product->description = $data['desc'];
//
//        $product->tips = json_encode($data['attributes']);
//        $product->sale_strategy = StrategyTypeEnum::NORMAL;
//        $product->unit_snap = json_encode(["name" => $data['unit']], JSON_UNESCAPED_UNICODE);
//        $product->unit_id = '0';
//
//        return $product->toArray();
//    }

//    public function dataToProductFromJd($data)
//    {
//        $product = new ProductModel();
//        $product->is_sku = 0;
//        $product->images = json_encode($data['images']);
//        $product->cover_img = $data['images'][0];
//        $product->thumb_count = $data['favouriteCount'];
//        $product->name = $data['name'];
//        $product->sub_title = $data['subTitle'];
//        if (!empty($data["videos"])) {
//            $product->video = $data["videos"][0]['url'];
//        }
//        $price = $data['price'];
//        $product->cost_price = $price;
//        $product->sale_price = $price;
//        $product->member_price = $price;
//        $product->origin_price = $data['originalPrice'];
//        $product->description = $data['desc'];
//
//        $product->sale_strategy = StrategyTypeEnum::NORMAL;
//        $product->unit_snap = json_encode(["name" => $data['unit']], JSON_UNESCAPED_UNICODE);
//        $product->unit_id = '0';
//        return $product->toArray();
//    }

//    public function dataToProductFromPdd($data)
//    {
//        $product = new ProductModel();
//        $product->is_sku = 0;
//
//        if (isset($data['banner'][0]['id'])) {
//            $data['banner'] = array_column($data['banner'], 'url');
//        }
//        $product->images = json_encode($data['banner']);
//        $product->cover_img = $data['hdThumbUrl'];
//
//
//        $product->name = $data['goodsName'];
//        $product->sub_title = $data['goodsDesc'];
//        if (!empty($data["video"])) {
//            $product->video = $data["video"][0];
//        }
//        $price = $data['maxGroupPrice'];
//        $product->cost_price = $data['minNormalPrice'];
//        $product->sale_price = $price;
//        $product->member_price = $data['minGroupPrice'];
//        $product->origin_price = $data['maxGroupPrice'];;
//        $content = "";
//        if (isset($data['detail'])) {
//            $data['detail'] = array_column($data['detail'], 'url');
//        }
//        foreach ($data['detail'] as $k => $v) {
//            $content .= "<p><img width='100%' src=$v></p>";
//        }
//        $product->description = $content;
//
//        $product->sale_strategy = StrategyTypeEnum::NORMAL;
//        $product->unit_snap = json_encode(["name" => $data['unit']], JSON_UNESCAPED_UNICODE);
//        $product->unit_id = '0';
//
//        return $product->toArray();
//    }

//    public function dataToProductFromAlbb($data)
//    {
//        $product = new ProductModel();
//        $product->is_sku = 0;
//        if (!empty($data['images'])) {
//            $product->images = json_encode($data['images']);
//            $product->cover_img = $data['images'][0];
//        }
//        $product->name = $data['title'];
//        $product->sub_title = $data['subTitle'];
//
//        $product->origin_price = $data['mixAmount'];
//        $price = $data['displayPrice'];
//
//        $price = str_replace("￥", "", $price);
//        $arr = explode("-", $price);
//        $price = $arr[0];
//        $product->cost_price = $price;
//        $product->sale_price = $price;
//        $product->member_price = $price;
//        $product->description = empty($data['desc']) ? '' : $data['desc'];
//        $product->sale_strategy = StrategyTypeEnum::NORMAL;
//        $product->unit_snap = json_encode(["name" => $data['unit']], JSON_UNESCAPED_UNICODE);
//        $product->unit_id = '0';
//
//        return $product->toArray();
//    }

//    public function dataToProductFromSN($data)
//    {
//        $product = new ProductModel();
//        $product->is_sku = 0;
//        $product->images = json_encode($data['images']);
//        $product->cover_img = $data['images'][0];
//        $product->name = $data['title'];
//
//        $product->origin_price = $data['price'];
//        $product->cost_price = $data['price'];
//        $product->sale_price = $data['price'];
//        $product->member_price = $data['price'];
//        $product->description = empty($data['desc']) ? '' : $data['desc'];
//        $product->sale_strategy = StrategyTypeEnum::NORMAL;
//        $product->unit_id = '0';
//        return $product->toArray();
//    }

//    function getQuerystr($url, $key)
//    {
//        $res = '';
//        $a = strpos($url, '?');
//        if ($a !== false) {
//            $str = substr($url, $a + 1);
//            $arr = explode('&', $str);
//            foreach ($arr as $k => $v) {
//                $tmp = explode('=', $v);
//                if (!empty($tmp[0]) && !empty($tmp[1])) {
//                    $barr[$tmp[0]] = $tmp[1];
//                }
//            }
//        }
//        if (!empty($barr[$key])) {
//            $res = $barr[$key];
//        }
//        return $res;
//    }
//
//    function getIdFromUrl($url, $needShop = false)
//    {
//        $url = substr($url, 0, strrpos($url, '.html'));
//
//        $id = substr(strstr($url, '/'), 1);
//        $arr = explode("/", $id);
//        $length = count($arr);
//        if ($needShop) {
//            if ($length > 1) {
//                $id = ltrim($arr[$length - 1], "0") . "&shopid=" . $arr[$length - 2];
//            } else {
//                $id = $arr[0] . "&shopid=000000";
//            }
//        } else {
//            $id = $arr[$length - 1];
//        }
//        return $id;
//    }
//
//    function provideSource($url)
//    {
//        $result = [];
//
//        $exsist = strstr($url, self::TM);
//        if ($exsist) {
//            $result['source'] = self::TM;
//            $result['url'] = "https://api03.6bqb.com/tmall/detail";
//            $result['id'] = $this->getQuerystr($exsist, "id");
//            return $result;
//        }
//        $exsist = strstr($url, self::TB);
//        if ($exsist) {
//            $result['source'] = self::TB;
//            $result['url'] = "https://api03.6bqb.com/taobao/detail";
//            $result['id'] = $this->getQuerystr($exsist, "id");
//            return $result;
//        }
//
//        $exsist = strstr($url, self::PDD);
//        if ($exsist) {
//            $result['source'] = self::PDD;
//            $result['url'] = "https://api03.6bqb.com/pdd/detail";
//            $result['id'] = $this->getQuerystr($exsist, "goods_id");
//            return $result;
//        }
//        $exsist = strstr($url, self::ALBB);
//        if ($exsist) {
//            $result['source'] = self::ALBB;
//            $result['url'] = "https://api03.6bqb.com/alibaba/detail";
//            $result['id'] = $this->getIdFromUrl($exsist);
//            return $result;
//        }
//
//        $exsist = strstr($url, self::JD);
//        if ($exsist) {
//            $result['source'] = self::JD;
//            $result['url'] = "https://api03.6bqb.com/jd/detail";
//            $result['id'] = $this->getIdFromUrl($exsist);
//            return $result;
//        }
//        $exsist = strstr($url, self::SUNING);
//        if ($exsist) {
//            $result['source'] = self::SUNING;
//            $result['url'] = "https://api03.6bqb.com/suning/detail";
//            $result['id'] = $this->getIdFromUrl($exsist, true);
//            return $result;
//        }
//
//    }

}
/**********************End Of AppVersion 控制层************************************/ 


