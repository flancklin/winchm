<?php
namespace rbac_f\helpers;


class ArrayHelper{

    /**
     * @param $fieldList //数据库支持的字段  ['name','age','sex']
     * @param $params   //从页面获取的参数  ['name' => '张三', 'age' => 10, 'sex'=>1]
     * @param $data     //从数据库获取的记录['name' => '张三', 'age' => 11, 'sex' => 1]
     * @return array
     */
    public static function justNeed($fieldList, $params, $data = []){
        $backData = [];
        if(empty($data)){
            foreach ($fieldList as $field){
                if(isset($params[$field])){
                    $backData[$field] = $params[$field];
                }
            }
        }else{
            foreach ($fieldList as $field){
                if(isset($params[$field]) && ($params[$field] != $data[$field])){
                    $backData[$field] = $params[$field];
                }
            }
        }
        return $backData;
    }
}