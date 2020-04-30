<?php
namespace rbac_f;

$classMap = [
    'Rbac',
    'abstracts' =>['StorageAbstract'],
    'classes' => ['RbacException','Permission', 'Role'],
    'helpers' => ['ArrayHelper', 'ErrorHelper']
];
foreach ($classMap as $key => $value){
    if(is_array($value)){
        foreach ($value as $v){
            include_once  __DIR__ . DIRECTORY_SEPARATOR. $key . DIRECTORY_SEPARATOR . $v .'.php';
        }
    }else{
        include_once  __DIR__ .DIRECTORY_SEPARATOR. $value .'.php';
    }

}