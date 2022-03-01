<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace fanyou\error;

use yii\web\HttpException;

/**
 * UnprocessableEntityHttpException represents an "Unprocessable Entity" HTTP
 * exception with status code 422.
 *
 * Use this exception to inform that the server understands the content type of
 * the request entity and the syntax of that request entity is correct but the server
 * was unable to process the contained instructions. For example, to return form
 * validation errors.
 *
 * @link http://www.webdav.org/specs/rfc2518.html#STATUS_422
 * @author Jan Silva <janfrs3@gmail.com>
 * @since 2.0.7
 */
class FanYouHttpException extends HttpException
{
    /**
     * FanYouHttpException constructor.
     * @param $errorCode
     * @param null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($errorCode,  $message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($errorCode, $message, $code, $previous);
    }
}
