<?php

namespace App\Api;

class PrintService extends RpcService{

    /**
     * 打印接口
     *
     * @param $machineCode string 机器码
     * @param $content string 打印内容
     * @param $originId string 商户系统内部订单号，要求32个字符内，只能是数字、大小写字母
     * @return mixed
     */
    public function index($machineCode, $content, $originId)
    {
        return $this->client->call('print/index', array('machine_code' => $machineCode, 'content' => $content, 'origin_id' => $originId));
    }

    public function picIndex($machineCode, $picture_url, $originId)
    {
        return $this->client->call('pictureprint/index', array('machine_code' => $machineCode, 'picture_url' => $picture_url, 'origin_id' => $originId));
    }
}