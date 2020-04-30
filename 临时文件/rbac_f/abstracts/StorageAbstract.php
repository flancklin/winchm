<?php
namespace rbac_f\abstracts;

/**
 * 在实现StorageAbstract过程中可以解决两个问题
 * 1、开发者自行定义用哪个数据库，或者是否启用分库分表。
 * 2、rbac涉及到的表，可以在继承中自行换名
 * Class StorageAbstract
 * @package rbac_f\abstracts
 */
abstract class StorageAbstract{
    public $tableRole = "rbac_role";
    public $tablePermission = "rbac_permission";
    public $tableUserRole = "rbac_user_role";

    /**
     * 对数据库的新增操作
     * @param $tableName
     * @param array $AssociativeArray //field=>value
     * @return mixed
     */
    abstract  function add($tableName, array $AssociativeArray);

    /**
     * 对数据库的更新操作
     * @param $tableName
     * @param array $where
     * @param array $AssociativeArray //field=>value
     * @return bool|integer
     */
    abstract  function alert($tableName, array $where, array $AssociativeArray);

    /**
     * 对数据库的查询操作
     * ((a=1 and b>2) or (c<>3 and d in (4,5,6) and e like '%7%')))
     *[
     *    'or',
     *    [
     *        'and',
     *        ['a', '=', 1],
     *        ['b', '>', 2],
     *    ],
     *    [
     *        'and',
     *        ['c', '<>', 3],
     *        ['d', 'in', '(4,5,6)'],
     *        ['e', 'like', '%7%']
     *    ]
     *];
     * @param $tableName
     * @param array $where
     * @param array $params //比如 limit/offset/order by等
     * @return bool|integer
     */
    abstract  function select($tableName, array $where, array $params = []);

    /**
     * 对数据库的删除操作
     * @param $tableName
     * @param $where
     * @return bool|integer
     */
    abstract  function delete($tableName, array $where);

    /**
     * 创建表
     * @param array $tableSql
     * @return mixed
     */
    abstract  function createTables(array $tableSql);

    public function initTable(){
        $createTableSql = [
            'role' => "CREATE TABLE IF NOT EXISTS `{$this->tableRole}` (
                    `role_id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色的称呼',
                    `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色的功能描述',
                    `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-正常，2-暂停，0-删除',
                    `permission_page`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色拥有的[页面]权限ID列表【,1,2,3,】',
                    `permission_precision`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色拥有的[细粒]权限ID列表【,1,2,3,】',
                    `created_time` int(11) NOT NULL,
                    PRIMARY KEY (`role_id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
            'permission' => "CREATE TABLE IF NOT EXISTS `{$this->tablePermission}` (
                    `permission_id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限的名称',
                    `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限的功能描述',
                    `type` tinyint(2) NOT NULL COMMENT '1-纯页面权限，2-细粒权限',
                    `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-正常，2-暂停，0-删除',
                    `rule_class`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限规则调用的类',
                    `rule_params`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '权限规则调用类的时候需要的参数',
                    `page_permission`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '纯页面权限，当前页面存在哪些细粒权限【,1,2,3,】',
                    `created_time` int(11) NOT NULL,
                    PRIMARY KEY (`permission_id`),
                    UNIQUE KEY `name` (`name`),
                    INDEX `status` (`status`)
                ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
            'user_role' => "CREATE TABLE IF NOT EXISTS `{$this->tableUserRole}` (
                   `uid` varchar(64) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci NOT NULL,
                   `role_id` int(11) NOT NULL,
                   `created_at` int(11) NOT NULL,
                   PRIMARY KEY (`uid`,`role_id`),
                   KEY `uid` (`uid`),
                   CONSTRAINT `relation_role` FOREIGN KEY (`role_id`) REFERENCES `{$this->tableRole}` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
                )"
        ];
        return $this->createTables($createTableSql);
    }
}