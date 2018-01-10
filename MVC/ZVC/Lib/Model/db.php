<?php
namespace Lib\Model;

abstract class db
{
    /*
    连接服务器
    parms $dsn
    parms $u user
    parms $p password
    parms $db database
    return bool
     */
    abstract public function mConn($dsn, $u, $p);

    abstract public function mQuery($sql);

    abstract public function mGetAll($sql);

    abstract public function mGetRow($sql);

    abstract public function mGetOne($sql);

    abstract public function mExec($table, $data, $act, $where);

    abstract public function mLastId();

    abstract public function mAffectedRows($sql);
}
