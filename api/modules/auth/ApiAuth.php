<?php
/**
 * Created by PhpStorm.
 * User: wangdong
 * Date: 2018/8/23
 * Time: 14:11
 */

namespace api\modules\auth;

use yii\filters\auth\HttpBearerAuth;
use yii\web\UnauthorizedHttpException;

class ApiAuth extends HttpBearerAuth
{

    public $realm = 'api';
    public $header = 'access-token';


    public function authenticate($user, $request, $response)
    {
        $authHeader = $request->getHeaders()->get($this->header);
        if ($authHeader !== null) {
            $identity = $user->loginByAccessToken($authHeader);
            if ($identity === null) {
                $this->challenge($response);
                $this->handleFailure($response);
            }
            return $identity;
        }
        return null;
    }

    public function challenge($response)
    {
        $response->getHeaders()->set('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
    }
    /**
     * @param $response
     * @throws UnauthorizedHttpException
     */
//    public function handleFailure($response)
//    {
//        throw new UnauthorizedHttpException('登录超时');
//    }
}
