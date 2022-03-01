<?php

namespace fanyou\components;

use fanyou\models\common\Attachment;
use fanyou\error\FanYouHttpException;
use fanyou\tools\UploadHelper;


/**
 * Class PhotoMerge
 * @package fanyou\components
 * @author: Administrator
 * @E-mail: admin@163.com
 * @date: 2020-07-01 15:38
 */
class PhotoMerge
{
    /**
     * 生成宣传海报
     * @param array  参数,包括图片和文字
     * @param string $filename 生成海报文件名,不传此参数则不生成文件,直接输出图片
     * @return [type] [description]
     */
    public static function createPoster($config = array(), $filename = "")
    {
        //如果要看报什么错，可以先注释调这个header
        if (empty($filename)) header("content-type: image/png");
        $imageDefault = array(
            'left' => 0,
            'top' => 0,
            'right' => 0,
            'bottom' => 0,
            'width' => 100,
            'height' => 100,
            'opacity' => 100
        );
        $textDefault = array(
            'text' => '',
            'left' => 0,
            'top' => 0,
            'fontSize' => 32,       //字号
            'fontColor' => '255,255,255', //字体颜色
            'angle' => 0,
        );
        $background = $config['background'];//海报最底层得背景
//背景方法

        $backgroundInfo = getimagesize($background);
        $backgroundFun = 'imagecreatefrom' . image_type_to_extension($backgroundInfo[2], false);
        $background = $backgroundFun($background);
        $backgroundWidth = imagesx($background);  //背景宽度
        $backgroundHeight = imagesy($background);  //背景高度
        $imageRes = imageCreatetruecolor($backgroundWidth, $backgroundHeight);
        $color = imagecolorallocate($imageRes, 0, 0, 0);
        imagefill($imageRes, 0, 0, $color);
        //imageColorTransparent($imageRes, $color);  //颜色透明
        imagecopyresampled($imageRes, $background, 0, 0, 0, 0, imagesx($background), imagesy($background), imagesx($background), imagesy($background));
//处理了图片

        if (!empty($config['image'])) {

            foreach ($config['image'] as $key => $val) {

                if (!empty($val['url'])) {

                $val = array_merge($imageDefault, $val);
                $info = getimagesize($val['url']);

                $function = 'imagecreatefrom' . image_type_to_extension($info[2], false);
                if ($val['stream']) {   //如果传的是字符串图像流
                    $info = getimagesizefromstring($val['url']);
                    $function = 'imagecreatefromstring';
                }
                $res = $function($val['url']);
                $resWidth = $info[0];
                $resHeight = $info[1];
//建立画板 ，缩放图片至指定尺寸
                $canvas = imagecreatetruecolor($val['width'], $val['height']);
                imagefill($canvas, 0, 0, $color);
//关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
                imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'], $resWidth, $resHeight);
                $val['left'] = $val['left'] < 0 ? $backgroundWidth - abs($val['left']) - $val['width'] : $val['left'];
                $val['top'] = $val['top'] < 0 ? $backgroundHeight - abs($val['top']) - $val['height'] : $val['top'];
//放置图像
                imagecopymerge($imageRes, $canvas, $val['left'], $val['top'], $val['right'], $val['bottom'], $val['width'], $val['height'], $val['opacity']);//左，上，右，下，宽度，高度，透明度
            }
            }
        }
//处理文字
        if (!empty($config['text'])) {
            foreach ($config['text'] as $key => $val) {
                $val = array_merge($textDefault, $val);
                list($R, $G, $B) = explode(',', $val['fontColor']);
                $fontColor = imagecolorallocate($imageRes, $R, $G, $B);
                $val['left'] = $val['left'] < 0 ? $backgroundWidth - abs($val['left']) : $val['left'];
                $val['top'] = $val['top'] < 0 ? $backgroundHeight - abs($val['top']) : $val['top'];
                imagettftext($imageRes, $val['fontSize'], $val['angle'], $val['left'], $val['top'], $fontColor, $val['fontPath'], $val['text']);
            }
        }
//生成图片


        if (!empty($filename)) {
            $res = imagejpeg($imageRes, $filename, 90); //保存到本地
            imagedestroy($imageRes);
            if (!$res) return false;
            return $filename;
        } else {
            imagejpeg($imageRes);     //在浏览器上显示
            imagedestroy($imageRes);
        }
    }

    /**
     * 缩放图片，并组装成圆角
     * @param string $imgpath
     * @param float $ratio
     * @return string
     */
    public static function yuan_img($imgpath = './tx.jpg',$ratio=0.5)
    {
        $ext = pathinfo($imgpath);
        $src_img = null;


        switch ($ext['extension']) {
            case '':

            case 'jpg':
                $src_img = imagecreatefromjpeg($imgpath);
                break;
            case 'png':
                $src_img = imagecreatefrompng($imgpath);
                break;
        }
        $wh = getimagesize($imgpath);

        $org_w = $wh[0];
        $org_h = $wh[1];

        $w =  min($org_w, $org_h) * $ratio;
        $h = $w;

        $img = imagecreatetruecolor($w, $h); //创建一个彩色的底图

        //这一句一定要有
        imagesavealpha($img, true);

        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r = $w / 2; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x/$ratio, $y/$ratio);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x, $y, $rgbColor);
                }
            }
        }

        $filename = sys_get_temp_dir() .'/'. time() . '.jpg';

        imagejpeg($img, $filename);
        imagedestroy($img);
        return $filename;
    }

    /**
     * 变换尺寸
     * @param $imgsrc
     * @param $imgwidth
     * @param $imgheight
     * @return string
     */
    public static function resizejpg($imgsrc, $imgwidth, $imgheight)
    {
        $arr = getimagesize($imgsrc);
        header("Content-type: image/jpg");
        $imgWidth = $imgwidth;
        $imgHeight = $imgheight;
        $imgsrc = imagecreatefromjpeg($imgsrc);
        $image = imagecreatetruecolor($imgWidth, $imgHeight); //创建一个彩色的底图
        $filename = sys_get_temp_dir() . time() . '.jpg';
        imagecopyresampled($image, $imgsrc, 0, 0, 0, 0, $imgWidth, $imgHeight, $arr[0], $arr[1]);
        imagepng($image, $filename);
        imagedestroy($image);
        //  print_r($filename);exit;
        return $filename;
    }
    /**
     * 下载微信头像，并保存
     * @param $targetUrl
     * @param $headImg
     * @param  $userId
     * @return mixed
     * @throws FanYouHttpException
     * @throws \League\Flysystem\FileExistsException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public static function saveHeadImg($targetUrl,$headImg,$userId)
    {
        //创建临时文件
        $imgPath = tempnam(sys_get_temp_dir(), 'image');
       // rename($imgPath, $imgPath );
        file_put_contents($imgPath, file_get_contents($headImg));
        $upload = new UploadHelper([], Attachment::UPLOAD_TYPE_IMAGES);
        $urlPath=$upload->save($targetUrl,$imgPath,$userId.'.jpg')['accessUrl'];

        return $urlPath;
    }
}