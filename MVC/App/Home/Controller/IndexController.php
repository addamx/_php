<?php

namespace App\Home\Controller;

use Lib\Controller;
use Lib\Model;

class IndexController extends Controller
{
    public function index()
    {
        //var_dump($_SERVER);
        $dbc = new Model('user');
        if ($_POST) {
            $dbc->create();
            var_dump($dbc->data);
            $dbc->update();
        }
        $this->assign('a', '表单');
        $this->display();
    }
}
