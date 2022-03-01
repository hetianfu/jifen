<?php

namespace fanyou\components\uploaddrive;

use fanyou\enums\HttpErrorEnum;
use fanyou\error\FanYouHttpException;
use fanyou\tools\helpers\RegularHelper;
use fanyou\tools\StringHelper;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Class Local
 * @package fanyou\components\uploaddrive
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-10 10:41
 */
class Local extends DriveInterface
{

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
    { // 判断是否追加

        if ($cert) {
            $this->adapter = new \League\Flysystem\Adapter\Local(Yii::getAlias('@api'));
        } else {
            $this->adapter = new \League\Flysystem\Adapter\Local(Yii::getAlias('@attachment'));
        }
    }
    public function delete($fileKey)
    {
        // TODO: Implement delete() method.
    }
    /**
     * 上传
     * @param $imagePath
     * @param $localPath
     * @param string $fileName
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \League\Flysystem\FileExistsException
     */
    public function upload($imagePath, $localPath, $fileName = '')
    {
        if (!empty($localPath)) {
            $prex = str_replace('/', '', Yii::getAlias('@attachurl')) . '/';
            $this->mkdirs($prex . $imagePath);

            $fileName = $imagePath . '/' . $fileName;
            $obslPath = Yii::getAlias('@root') . '/web/' . $prex . $fileName;
            copy($localPath, $obslPath);
        } else {
            $this->entity();
            $file = UploadedFile::getInstanceByName('file');
            if (empty($fileName)) {
                $fileName = StringHelper::uuid();
            } else {
                $fileName = substr($fileName, 0, strpos($fileName, '.'));
            }
            $extendName = (trim(strchr($file->name, '.'), '.'));
            if (strlen($extendName) <= 1) {
                if ($file->type == 'image/jpeg') {
                    $extendName .= 'jpg';
                }
                if ($file->type == 'image/png') {
                    $extendName .= 'png';
                }
            }
            $fileName .= '.' . $extendName;
            $fileName = $imagePath . '/' . $fileName;
            $size = $file->size;
            $stream = fopen($file->tempName, 'r+');

            $result = $this->filesystem->writeStream($fileName, $stream);

            if (!$result) {
                throw new NotFoundHttpException('文件写入失败');
            }
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
        $url = Yii::$app->request->hostInfo . Yii::getAlias('@attachurl') . '/' . $fileName;

        $imgInfo = getimagesize(Yii::getAlias('@attachment') . '/' . $fileName);
        $fileData['width'] = $imgInfo[0] ?? 0;
        $fileData['height'] = $imgInfo[1] ?? 0;
        $fileData['accessUrl'] = $url ?? '';

        $fileData['key'] = Yii::getAlias('@attachurl') . '/' . $fileName;
        $fileData['size'] = $size;
        return $fileData;
    }

    /**
     * 上传微信证书文件 ，如果文件存在，完全覆盖
     * @param $imagePath
     * @return mixed
     * @throws FanYouHttpException
     * @throws NotFoundHttpException
     */
    public function uploadCert($imagePath,$realUpload=true)
    {
        $this->adapter = new \League\Flysystem\Adapter\Local(Yii::getAlias('@api'));
        $this->entity();
        $file = UploadedFile::getInstanceByName('file');
        $fileName = $imagePath . '/' . $file->name;
        $url = Yii::getAlias('@api') . '/' . $fileName;

        if($realUpload){
        file_exists($url) && unlink($url);
        $size = $file->size;
        $stream = fopen($file->tempName, 'r+');
        try {
            $result = $this->filesystem->writeStream($fileName, $stream);
        } catch (\Exception $e) {
            throw new FanYouHttpException(HttpErrorEnum::Unprocessable_Entity, '证书已存在');
        }
        if (!$result) {
            throw new NotFoundHttpException('文件写入失败');
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        }
        $fileData['accessUrl'] = $url ?? '';

        $fileData['size'] = $size;
        return $fileData;
    }

    /**
     * 创建目录
     * @param $dir
     * @param int $mode
     * @return bool
     */
    private function mkdirs($dir, $mode = 0777)
    {
        $dir = Yii::getAlias('@root') . '/web/' . $dir;
        if (is_dir($dir) || @mkdir($dir, $mode, true))
            return TRUE;


    }

    public function uploadFormUrl($url, $fileName = '')
    {

    }
}