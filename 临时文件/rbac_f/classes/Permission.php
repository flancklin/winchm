<?php
namespace rbac_f\classes;

use rbac_f\abstracts\DbAbstract;
use rbac_f\helpers\ErrorHelper;
use rbac_f\traits\DbTrait;

class Permission {

    const TYPE_PAGE = 1;//页面
    const TYPE_PRECISION = 2;//细粒

    protected $storage = null;
    protected $tableName = '';
    protected $filedListAdd = ['name','desc','type','rule_class','rule_params','page_permission'];
    protected $filedListAlert = ['name', 'desc','rule_class','rule_params','page_permission'];
    protected $uniqueField = 'permission_id';

    public function __construct($storage)
    {
        $this->tableName = $this->storage->tablePermission;
        if(!$this->tableName) ErrorHelper::dealErr('缺少permission表');
    }
    use DbTrait;//增改查
}
