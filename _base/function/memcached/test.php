<?php
$db = new PDO('mysql:host=localhost;dbname=newshop;charset=utf8', 'root', 'net691029');

$memc = new Memcache();

$memc->addServer('localhost', 11211);

$sql = 'select id,name from newshop_goods where is_hot=1 limit 5';

// 判断 memcached 中是否缓存热门商品,如果没有,则查询数据库
$hot = array();
if (!($hot = $memc->get($sql))) {
    $rs  = $db->query($sql);
    $hot = $rs->fetchAll();
    echo '<font color="red">查询自数据库</font><br/>';
    var_dump($hot);
    //从数据库取得数据后,把数据写入 memcached
    $memc->add($sql, $hot, 0, 300); // 并设置有效期 300 秒
} else {
    echo '<font color="red">查询自 memcached</font><br/>';
    var_dump($hot);
}
