<?php
namespace fanyou\components\uploaddrive;
require_once 'AliCloud/autoload.php';

use fanyou\common\AliOssModel;
use fanyou\components\SystemConfig;
use fanyou\components\systemDrive\ArrayColumn;
use fanyou\enums\SystemConfigEnum;
use fanyou\enums\StatusEnum;
use OSS\Core\OssException;
use OSS\OssClient;
use yii\base\Component;
use yii\web\UploadedFile;

/**
 * 阿里云对象存储组件
 *
 * @author leedong
 *        
 */
class AliOssComponent extends Component
{
    protected $config;
    private $ossClient;
    private $bucketName;
    private $endpoint;
    private $accessKeyId;
    private $accessKeySecret;
    public function __construct($config)
    {
        $service = new SystemConfig();
        $alisOssConfig=$this->fillAliOssConfig(
            ArrayColumn::getSystemConfigValue($service->getConfigInfo(true,SystemConfigEnum::ALI_OSS,StatusEnum::APP))

        );

        $this->create($alisOssConfig);

    }


    /**
     * 删除一个文件对象
     * @param string $fileKey
     * @return null|string
     */
    function delete(string $fileKey = ''): ? string
    {
        try {
            $this->ossClient->deleteObject($this->bucketName, $fileKey);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }
        return 'ok';
    }
    /**
     * 批量删除文件对象
     * @param array $fileKeys
     * @return null|string
     */
    function batchDelete(array $fileKeys): ? string
    {
        try {
            $this->ossClient->deleteObject($this->bucketName,$fileKeys);
        } catch(OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return null;
        }
        return 'ok';
    }
    /**
     * @param string $folder 上传地址目标
     * @param UploadedFile|null $file
     * @return array|null
     * @throws \yii\base\Exception
     */
    function upload(string $folder = '', UploadedFile $file = null): ?array
    {
        try{
       $fileKey = $folder .  "." . $file->extension;
       $size=  $file->size;
       $this->ossClient->uploadFile($this->bucketName,$fileKey,$file->tempName );
         } catch(OssException $e) {

        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        print_r($e->getMessage());exit;
        return  null;
        }
            $fileCdnUrl ="https://". $this->bucketName.'.'.$this->endpoint .'/'.$fileKey;
            return [
                'key' => $fileKey,
                'accessUrl' => $fileCdnUrl,
                'cosFolder' => $folder,
                'size' => $size
            ];
    }

    /**
     * 上传本地路径到阿里云
     * @param string $folder  上传地址目标
     * @param null $path 本地路径
     * @return array|null
     * @throws \yii\base\Exception
     */
    function uploadPath(string $folder = '', $path = null): ?array
    {
        try{
            $fileKey = $folder;
            $this->ossClient->uploadFile($this->bucketName,$fileKey,$path );
        } catch(OssException $e) {

            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            print_r($e->getMessage());exit;
            return  null;
        }
        $fileCdnUrl ="https://". $this->bucketName.'.'.$this->endpoint .'/'.$fileKey;
        return [
            'key' => $fileKey,
            'accessUrl' => $fileCdnUrl,
            'cosFolder' => $folder
        ];
    }


    /**
     * @return mixed|void
     * @throws \Exception
     */
    protected function create(AliOssModel $AliOss)
    {
        $this->endpoint = $AliOss->endpoint;
        $this->bucketName = $AliOss->bucket_name;
        $this->ossClient =new OssClient($AliOss->access_key_id,$AliOss->access_key_secret, $AliOss->endpoint);

    }
    /**
     * 序列化阿里云
     * @return array
     */
    public function  fillAliOssConfig($array):AliOssModel {
        $aliOss=new AliOssModel();
        $aliOss->setAttributes($array,false);
        return  $aliOss;
    }
}

