<?php
namespace rbac_f\traits;

use rbac_f\helpers\ArrayHelper;
use rbac_f\helpers\DbFilterHelper;
use rbac_f\helpers\ErrorHelper;
use rbac_f\Rbac;

trait DbTrait{

    public function add($params){
        $data = ArrayHelper::justNeed($this->filedListAdd, $params);
        if(!DbFilterHelper::checkNeedField($data)) ErrorHelper::dealErr('数据错误');
        if($this->selectOne(['name=:name',[':name' => $data['name']]])) ErrorHelper::dealErr('名称已存在');
        return $this->storage->add($this->tableName, $data);
    }
    public function alert($uniqueID, $params){
        if(!$uniqueID) ErrorHelper::dealErr('缺少必要参数');
        $where = ["{$this->uniqueField}=:{$this->uniqueField}",[":{$this->uniqueField}" => $uniqueID]];
        //先过滤无用的字段
        $data = ArrayHelper::justNeed($this->filedListAlert, $params);
        if(!$data) ErrorHelper::dealErr('数据为空');
        //字段对比，找出发生变更的字段
        $record = $this->selectOne($where);
        $data = ArrayHelper::justNeed($this->filedListAlert, $data, $record);
        if(!$data) return true;//没有字段变更
        if(!DbFilterHelper::checkNeedField($data)) ErrorHelper::dealErr('数据错误');
        if(isset($data['name']) && $this->selectOne(['name=:name',[':name' => $data['name']]])) ErrorHelper::dealErr('名称已存在');
        return $this->storage->alert($this->tableName, $where, $data);
    }
//    public function delete($uniqueID){
//        if(!$uniqueID) ErrorHelper::dealErr('缺少必要参数');
//        $where = DbFilterHelper::buildStringWhere(["{$this->uniqueField}=:{$this->uniqueField}",[":{$this->uniqueField}" => $uniqueID]]);
//        return $this->storage->delete($this->tableName, $where);
//    }
    public function changeStatus($uniqueID, $status){
        if(!$uniqueID || !in_array($status, [Rbac::STATUS_DEL, Rbac::STATUS_OK, Rbac::STATUS_STOP])) ErrorHelper::dealErr('参数错误');
        $where = ["{$this->uniqueField}=:{$this->uniqueField}",[":{$this->uniqueField}" => $uniqueID]];
        $record = $this->selectOne($where);
        if(!$record) ErrorHelper::dealErr('未找到记录');
        if($record['status'] == $status) return true;
        return $this->storage->alert($this->tableName, $where, ['status' => $status]);
    }

    public function selectOne($where){
        $records = $this->storage->select($this->tableName, $where, ['limit' => 1]);
        return $records[0] ?? [];
    }
    public function select($where, $params = []){
        return $this->storage->select($this->tableName, $where, $params);
    }
}