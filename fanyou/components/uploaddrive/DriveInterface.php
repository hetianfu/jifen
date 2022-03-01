<?php

namespace fanyou\components\uploaddrive;

use fanyou\models\common\Attachment;
use fanyou\tools\helpers\RegularHelper;
use League\Flysystem\Filesystem;
use Overtrue\Flysystem\Cos\CosAdapter;
use Overtrue\Flysystem\Qiniu\QiniuAdapter;

/**
 * Interface DriveInterface
 * @package common\components\uploaddrive
 */
abstract class DriveInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var CosAdapter|OssAdapter|QiniuAdapter
     */
    protected $adapter;

    /**
     * 上传组件
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * DriveInterface constructor.
     * @param $config
     * @param bool $cert
     */
    public function __construct($config,$cert=false)
    {
        $this->config = $config;
        $this->create($cert);
    }

    /**
     * @return Filesystem
     */
    public function entity(): Filesystem
    {
        if (!$this->filesystem instanceof Filesystem) {

            $this->filesystem = new Filesystem($this->adapter);
        }

        return $this->filesystem;
    }

    /**
     * @param $baseInfo
     * @param $drive
     * @param $fullPath
     * @return mixed
     */
    public function getUrl($baseInfo, $drive, $fullPath)
    {
        $baseInfo = $this->baseUrl($baseInfo, $fullPath);

        if ($drive != Attachment::DRIVE_LOCAL && !RegularHelper::verify('url', $baseInfo['url'])) {
            $baseInfo['url'] = 'http://' . $baseInfo['url'];
        }
        return $baseInfo;
    }

    /**
     * 返回路由
     *
     * @param $baseInfo
     * @param $fullPath
     * @return $baseInfo
     */
    abstract protected function baseUrl($baseInfo, $fullPath);

    /**
     * @param bool $cert
     * @return mixed
     */
    abstract protected function create($cert=false);

    abstract public function upload($imagePath,$localPath,$fileName);
    abstract public function uploadFormUrl($url,$filePath);

    abstract public function delete($fileKey);
}