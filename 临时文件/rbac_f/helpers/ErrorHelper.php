<?php
namespace rbac_f\helpers;


use rbac_f\classes\RbacException;

class ErrorHelper{

    /**
     * 对该项目中错误的处理
     * @param $message
     * @param int $errCode
     * @throws RbacException
     */
    public static function dealErr($message, $errCode = 0){
        throw new RbacException($message, $errCode);
    }
}