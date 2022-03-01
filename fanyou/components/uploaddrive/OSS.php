<?php

namespace fanyou\components\uploaddrive;

use fanyou\components\PhotoMerge;
use fanyou\enums\HttpErrorEnum;
use fanyou\error\errorCode\ErrorProduct;
use fanyou\error\FanYouHttpException;
use fanyou\tools\StringHelper;
use Yii;
use fanyou\tools\helpers\RegularHelper;
use yii\web\UploadedFile;

/**
 * Class OSS
 * @package fanyou\components\uploaddrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-06-30 17:04
 */
class OSS extends DriveInterface
{
    private $client;

    /**
     * @param $baseInfo
     * @param $fullPath
     * @return mixed
     */
    protected function baseUrl($baseInfo, $fullPath)
    {
        $baseInfo['url'] = Yii::getAlias('@attachurl') . '/' . $baseInfo['url'];
        if ($fullPath == true && !RegularHelper::verify('url', $baseInfo['url'])) {
            $baseInfo['url'] = Yii::$app->request->hostInfo . $baseInfo['url'];
        }
        return $baseInfo;
    }

    /**
     * 初始化
     * @param bool $cert
     * @return mixed|void
     */
    protected function create($cert = false)
    {
        $this->client = new  AliOssComponent($this->config);
    }

    /**
     * @param $imagePath  上传地址路径
     * @param string $localPath 本地文件路径
     * @param string $fileName 上传地址的文件名
     * @return mixed
     * @throws FanYouHttpException
     * @throws \yii\base\Exception
     */
    public function upload($imagePath, $localPath = '', $fileName = '')
    {
        if (!empty($imagePath)) {
            $imagePath .= '/';
        }
        if (empty($fileName)) {
            $fileName = StringHelper::uuid();
            $fileName = date('Ymd', time()) . $fileName;
            $extendName = (trim(strchr($localPath, '.'), '.'));
            $cosFolder = $imagePath . $fileName . $extendName;

        } else {
            $originName = $fileName;
            $cosFolder = $imagePath . $originName;
        }

        if (empty($localPath)) {
            $file = UploadedFile::getInstanceByName('file');
            $extendName = (trim(strchr($file->name, '.'), '.'));
            if (strlen($extendName) <= 1) {
                if (strlen($extendName) == 0) {
                    $file->name .= '.';
                }
                if ($file->type == 'image/jpeg') {
                    $file->name .= 'jpg';
                }
                if ($file->type == 'image/png') {
                    $file->name .= 'png';
                }
            }
            $fileData = $this->client->upload($cosFolder, $file);
        } else {
            $fileData = $this->client->uploadPath($cosFolder, $localPath);
        }
        if (empty($fileData)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, 'ERROR');
        } else {
            // 判断图片的宽高
            if (isset($_FILES['file'])) {
                $imageInfo = getimagesize($_FILES['file']['tmp_name']);
                $fileData['width'] = $imageInfo['0'];
                $fileData['height'] = $imageInfo['1'];
            } else {
                $fileData['width'] = 800;
                $fileData['height'] = 800;
            }
            return $fileData;
        }
    }


    /**
     *从网络下载图片，并上传到阿里云
     */
    public function uploadFormUrl($url, $fileName = '')
    {   $fileName.=".jpg";
        $config = array(
            'background'=>$url,
        );
        $filePath =  sys_get_temp_dir() . '/'.$fileName;

        if(! PhotoMerge::createPoster($config,$filePath)){
            throw  new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity,ErrorProduct::SYSTEM_ERROR);
        }

        $fileData = $this->client->uploadPath(Yii::$app->params['downloadImage']['product']. '/'.$fileName,$filePath);
        if (empty($fileData)) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, 'ERROR');
        } else {
            // 判断图片的宽高
            if (isset($_FILES['file'])) {
                $imageInfo = getimagesize($_FILES['file']['tmp_name']);
                $fileData['width'] = $imageInfo['0'];
                $fileData['height'] = $imageInfo['1'];
            } else {
                $fileData['width'] = 800;
                $fileData['height'] = 800;
            }
            return $fileData;
        }
    }


    public function delete($fileKey)
    {
        return $this->client->delete($fileKey);
    }
}