<?php
/*
实现一致性哈希分布的核心功能
 */

//需要一个吧字符串转成整数的接口
interface hasher
{
    public function __hash($str);
}

interface distribution
{
    public function lookup($key);
}

class Consistent implements hasher, distribution
{
    protected $_nodes   = array();
    protected $_postion = array();

    protected $_mul = 64; //每个节点对应64个虚节点

    public function __hash($str)
    {
        return sprintf('%u', crc32($str)); //把字符串转成32位符号整数
    }

    public function lookup($key)
    {
        $point = $this->__hash($key);
        $node  = current($this->_postion); //先取圆环上最小的一个节点, 当成结果
        foreach ($this->_postion as $k => $v) {
            if ($point <= $k) {
                $node = $v;
                break;
            }
        }
        reset($this->_postion);
        return $node;
    }

    public function addNode($node)
    {
        if (isset($this->_nodes[$node])) {
            return;
        }
        for ($i = 0; $i < $this->_mul; $i++) {
            $pos                   = $this->__hash($node . '-' . $i);
            $this->_postion[$pos]  = $node;
            $this->_nodes[$node][] = $pos;
        }
        $this->_sortPos();
    }

    public function delNodel($node)
    {
        if (!isset($this->_nodes[$node])) {
            return;
        }
        foreach ($this->_nodes[$node] as $k) {
            unset($this->_postion[$k]);
        }
        unset($this->_nodes[$node]);
    }

    public function _sortPos()
    {
        ksort($this->_postion, SORT_REGULAR);
    }

}

$con = new Consistent();
$con->addNode('a');
$con->addNode('b');
$con->addNode('c');
$key = 'www.x.com';

echo $con->lookup($key);
