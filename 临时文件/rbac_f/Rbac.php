<?php
namespace rbac_f;


use rbac_f\abstracts\StorageAbstract;
use rbac_f\classes\Permission;
use rbac_f\classes\RbacException;
use rbac_f\classes\Role;
use rbac_f\helpers\ErrorHelper;

class Rbac
{
    const STATUS_OK = 1;//正常
    const STATUS_DEL = 0;//删除
    const STATUS_STOP = 2;//暂停

    private $storage = null;

    public function __construct(StorageAbstract $storage)
    {
        $this->storage = $storage;
        if(!$this->storage){
            throw new RbacException("请初始化存储方式");
        }
    }

    public function initTable(){
        return $this->storage->initTable();
    }

    public function createPermission($data){
        $oPermission = new Permission($this->storage);
        return $oPermission->add($data);
    }

    public function createRole($data){
        $oRole = new Role($this->storage);
        return $oRole->add($data);
    }

    public function addPermissionToRole($roleID, $permissionID){
        $oRole = new Role($this->storage);
        $oPermission = new Permission($this->storage);

        $role = $oRole->selectOne(['role_id=:role_id', [':role_id' => $roleID]]);
        if(!$role) ErrorHelper::dealErr('未找到角色');
        $permission = $oPermission->selectOne(['permission_id=:permission_id', [':permission_id' => $permissionID]]);
        if(!$permission) ErrorHelper::dealErr('权限不存在');

        $isPagePermission = $permission['type'] == Permission::TYPE_PAGE ? true : false;
        $permissionStr = $isPagePermission ? $role['permission_page'] : $role['permission_precision'];
        if($permissionStr){
            if(stripos(',' . $permissionID . ',', $permissionStr) !== false) return true;//原来的已经有这个权限了
            $permissionArr = explode(',', trim($permissionStr, ','));
            array_push($permissionArr, $permissionArr);
            sort($permissionArr);
            $savePermission = ',' . join(',', $permissionArr) . ',';
        }else{
            $savePermission = ',' . $permissionID . ',';
        }
        return $oRole->alert($role['id'], [($isPagePermission ? 'permission_page' : 'permission_precision') => $savePermission]);
    }

    public function deletePermissionToRole($roleID, $permissionID){
        $oRole = new Role($this->storage);
        $oPermission = new Permission($this->storage);

        $role = $oRole->selectOne(['role_id=:role_id', [':role_id' => $roleID]]);
        if(!$role) ErrorHelper::dealErr('未找到角色');
        $permission = $oPermission->selectOne(['permission_id=:permission_id', [':permission_id' => $permissionID]]);
        if(!$permission) ErrorHelper::dealErr('权限不存在');

        $isPagePermission = $permission['type'] == Permission::TYPE_PAGE ? true : false;
        $permissionStr = $isPagePermission ? $role['permission_page'] : $role['permission_precision'];
        if(!$permissionStr) return true;
        if(stripos(',' . $permissionID . ',', $permissionStr) === false) return true;
        $permissionArr = explode(',', trim($permissionStr, ','));
        $key=array_search($permissionID, $permissionArr);
        array_splice($permissionArr,$key,1);
        sort($permissionArr);
        $savePermission = ',' . join(',', $permissionArr) . ',';
        return $oRole->alert($role['id'], [($isPagePermission ? 'permission_page' : 'permission_precision') => $savePermission]);
    }

}