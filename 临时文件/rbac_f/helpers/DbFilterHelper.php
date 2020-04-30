<?php
namespace rbac_f\helpers;


class DbFilterHelper{

    public static function checkNeedField($data){
        if(!$data) ErrorHelper::dealErr('没有数据');
//        $rule = [
//            'permission_id' =>[
//                'type' => 'int',
//                'length' => 11,
//                'de'
//            ]
//        ];
        return true;
    }
}