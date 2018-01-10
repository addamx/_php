<?php
namespace Lib;

use Lib\Model\myPDO;

//protect $table, $dbc, $pk, $fields, $_auto, $_valid, $error
//__construct->实例化
//table
//_facade
//_autofill
//_validate + getErr + check

//基本增删改
//查(select, find, insert_id)

class Model
{
    protected $prefix = '';
    protected $table  = null;
    protected $dbc    = null;
    protected $pk     = 'id';
    protected $fields = array(); //限制字段
    protected $_auto  = array(); //自动完成
    protected $_valid = array(); //验证
    protected $error  = array(); //验证中的错误
    public $data      = array();

    public function __construct($table)
    {
        $this->prefix = C('DB_PREFIX');
        $this->table  = $this->prefix . $table;
        $this->dbc    = new myPDO();
    }

    //随时调用其他table
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function create($data = '')
    {
        if (empty($data)) {
            $this->data = $_POST;
            $data       = $_POST;
        }
        if (empty($data) || is_array($data)) {
            return false;
        }
        $data = $this->_facade($data);
        $data = $this->_autoFill($data);
        if (!$this->_validate) {
            var_dump($this->getErr());
        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __unset($name)
    {
        unset($this->data[$name]);
    }

    //增，返回影响的行数
    public function add($data = '')
    {
        if (empty($data)) {
            return $this->dbc->mExec($this->table, $this->data, 'INSERT');
        }
        return $this->dbc->mExec($this->table, $data, 'INSERT');
    }

    //删，返回影响的行数
    public function del($id)
    {
        return $this->dbc->mAffectedRows("DELETE FROM " . $this->table . " WHERE " . $this->pk . "=$id");
    }

    //改，返回影响的行数
    public function update($data = '', $id = '')
    {
        if (empty($data)) {
            return $this->dbc->mExec($this->table, $this->data, 'UPDATE', "WHERE " . $this->pk . "=" . $this->data[$this->pk]);
        }
        return $this->dbc->mExec($this->table, $data, 'UPDATE', "WHERE " . $this->pk . "=$id");
    }

    //查
    public function getAll()
    {
        return $this->dbc->mGetAll("SELECT * FROM " . $this->table);
    }

    public function getRow($id)
    {
        return $this->dbc->mGetRow("SELECT * FROM " . $this->table . " WHERE " . $this->pk . "=$id");
    }

    public function getOne($id)
    {
        return $this->dbc->mGetOne("SELECT * FROM " . $this->table . " WHERE " . $this->pk . "=$id");
    }

    public function _facade($array = array())
    {
        $data = array();
        foreach ($array as $k => $v) {
            if (in_array($k, $this->fields)) {
                $data[$k] = $v;
            }
        }
        return $data;
    }

    public function _autoFill($data = array())
    {
        foreach ($this->_auto as $v) {
            if (empty($data[$v[0]])) {
                switch ($v[1]) {
                    case 'value':
                        $data[$v[0]] = $v[2];
                        break;

                    case 'function':
                        $data[$v[0]] = call_user_func($v[2]);
                        break;

                    case 'class.function':
                        $data[$v[0]] = call_user_func(array($this, $v[2]));
                        break;

                }
            }
        }
        return $data;
    }

    public function _validate($data = array())
    {
        if (empty($this->_valid)) {
            return true;
        }

        $this->error = array();

        foreach ($this->_valid as $v) {
            switch ($v[1]) {
                case '1':
                    if (!isset($data[$v[0]])) {
                        $this->error[] = $v[2];
                        return false;
                    }
                    if (!isset($v[4])) {
                        $v[4] = '';
                    }
                    if (!$this->check($data[$v[0]], $v[3], $v[4])) {
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;

                case '0':
                    if (!isset($v[4])) {
                        $v[4] = '';
                    }
                    if (isset($data[$v[0]])) {
                        if (!$this->check($data[$v[0]], $v[3], $v[4])) {
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;

                case '2':
                    if (!isset($v[4])) {
                        $v[4] = '';
                    }
                    if (isset($data[$v[0]]) && !empty($data[$v[0]])) {
                        if (!$this->check($data[$v[0]], $v[3], $v[4])) {
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;
            }

        }
        return true;
    }

    public function getErr()
    {
        return $this->error;
    }

    public function check($value, $rule = '', $parm = '')
    {
        switch ($rule) {
            case 'require':
                return !empty($value);
            case 'number':
                return is_numeric($value);
            case 'in':
                $c = explode(',', $parm);
                return in_array($value, $c);
            case 'between':
                list($min, $max) = explode(',', $parm);
                return $value >= $min && $value <= $max;
            case 'length':
                list($min, $max) = explode(',', $parm);
                return strlen($value) >= $min && strlen($value) <= $max;
            case 'email':
                return (filter_var($value, FILTER_VALIDATE_EMAIL) !== false);
        }
    }

}
