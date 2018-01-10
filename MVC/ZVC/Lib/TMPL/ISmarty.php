<?php
namespace Lib\TMPL;

include BASEDIR . '/Vendor/Smarty/Libs/Smarty.class.php';
use \Smarty;

class ISmarty extends \Smarty
{
    public function __construct()
    {
        parent::__construct();
    }
}
