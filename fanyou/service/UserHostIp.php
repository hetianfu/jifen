<?php
namespace fanyou\service;

class UserHostIp  {

 public static function getIP(){

    if (getenv('HTTP_CLIENT_IP')) {
      $ip = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
      $ip = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('REMOTE_ADDR')) {
      $ip = getenv('REMOTE_ADDR');
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }


}

