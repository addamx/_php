<?php
namespace Lib;

class Controller
{
    protected $view = null;

    public function __construct()
    {
        $this->view = new \Lib\View();
        if (method_exists($this, '_initialize')) {
            $this->_initialize();
        }
    }

    public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '')
    {
        $this->view->display($templateFile, $charset, $contentType, $content, $prefix);
    }

    protected function assign($name, $value = '')
    {
        $this->view->assign($name, $value);
        return $this;
    }
}
