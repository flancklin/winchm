<?php
namespace rbac_f\classes;

use rbac_f\abstracts\StorageAbstract;
use rbac_f\helpers\ErrorHelper;
use rbac_f\traits\DbTrait;

class Role {

    protected $storage = null;
    protected $tableName = '';
    protected $filedListAdd = ['name','desc','permission_page','permission_precision'];
    protected $filedListAlert = ['name','desc','permission_page','permission_precision'];
    protected $uniqueField = 'role_id';

    public function __construct(StorageAbstract $storage)
    {
        $this->storage = $storage;
        if(!$this->storage) ErrorHelper::dealErr('请初始化存储方案');
        $this->tableName = $this->storage->tableRole;
        if(!$this->tableName) ErrorHelper::dealErr('缺少permission表');
    }
    use DbTrait;//增改查

}
