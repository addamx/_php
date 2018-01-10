<?php
namespace Lib\Model;

use Lib\Log;

class myPDO extends db
{

    private static $dbc = null;

    public function __construct()
    {
        self::mConn(C('DB_DSN'), C('DB_USER'), C('DB_PASSWORD'));
    }

    public function mConn($dsn, $u, $p)
    {
        if (!self::$dbc) {
            try {
                self::$dbc = new \PDO($dsn, $u, $p);
                self::$dbc->exec("SET NAMES utf8");
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    public function mQuery($sql)
    {
        Log::write($sql);
        return self::$dbc->query($sql);
    }

    public function mGetAll($sql)
    {
        $rs = $this->mQuery($sql);
        return $rs ? $rs->fetchAll() : false;
    }

    public function mGetRow($sql)
    {
        $rs = $this->mQuery($sql);
        return $rs ? $rs->fetch(\PDO::FETCH_ASSOC) : false;
    }

    public function mGetOne($sql)
    {
        $rs = $this->mQuery($sql);
        return $rs ? $rs->fetchColumn() : false;
        //$row = $this->mGetRow($sql);
        //return $row ? reset($row) : false;
    }

    public function mExec($table, $data, $act = 'INSERT', $where = 'where 0')
    {
        $keys   = implode(",", array_keys($data));
        $values = implode("','", array_values($data));

        if ($act == 'INSERT') {
            $_sql = "INSERT INTO $table ($keys) VALUES ('$values');";
            Log::write($_sql);
            $sql = self::$dbc->prepare($_sql);
            $sql->execute();
            return $sql->rowCount();
        } else if ($act == 'UPDATE') {
            $_sql = "";
            foreach ($data as $k => $v) {
                $_sql .= $k . "='" . $v . "',";
            }
            $_sql = rtrim($_sql, ',');
            $_sql = "UPDATE $table SET $_sql $where;";
            Log::write($_sql);
            $sql = self::$dbc->prepare($_sql);
            $sql->execute();
            return $sql->rowCount();
        }
    }

    public function mLastId()
    {
        return self::$dbc->lastInsertId();
    }

    public function mAffectedRows($sql)
    {
        return self::$dbc->exec($sql);
    }
}
