<?php
/**
 * 视图类
 */
namespace Lib;

class View
{
    protected $tVar = array(); //模板输出变量
    //protected $theme = ''; //模板主题

    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->tVar = array_merge($this->tVar, $name);
        } else {
            $this->tVar[$name] = $value;
        }
    }

    public function get($name = '')
    {
        if ('' === $name) {
            return $this->tVar;
        }
    }

    public function display($tmpFile = '', $charset = '', $contentType = '', $content = '', $prefix = '')
    {
        Hook::listen('view_begin', $tmpFile);
        //解析并获取模板内容
        $content = $this->fetch($tmpFile, $content, $prefix);
        //输出模板内容
        $this->render($content, $charset, $contentType);
        Hook::listen('view_end');
    }

    private function render($content, $charset = '', $contentType)
    {
        if (empty($charset)) {
            $charset = C('DEFAUT_CHARSET');
        }
        if (empty($contentType)) {
            $contentType = C('TMPL_CONTENT_TYPE');
        }

        //网页字符编码

        @header('Content-Type:' . $contentType . ';charset=' . $charset);
        @header('Cache-control: ' . C('HTTP_CACHE_CONTROL'));
        @header('X-Powered-By:ZVC');

        echo $content;
    }

    public function fetch($templateFile = '', $content = '', $prefix = '')
    {
        $templateFile = $this->parseTemplate($templateFile);

        ob_start();
        ob_implicit_flush(0); //关闭自动flush()
        if ('php' == strtolower(C('TMPL_ENGINE_TYPE'))) {
            $_content = $content;
            extract($this->tVar, EXTR_OVERWRITE);
            if (empty($_content)) {
                include $templateFile;
            } else {
                eval('?>' . $content);
            }
        } else {
            $smarty = new \Lib\TMPL\ISmarty();
            $smarty->assign($this->tVar);
            $smarty->display($templateFile);
        }

        // 获取并清空缓存(等加上header后再输送)
        $content = ob_get_clean();
        return $content;
    }

    /**
     * 自动定位模板文件
     */

    public function parseTemplate($template = '')
    {
        if (is_file($template)) {
            return $template;
        }
        $depr     = C('TMPL_FILE_DEPR'); //controller与action的分隔符
        $template = str_replace(':', $depr, $template);

        if (!C('VIEW_PATH')) {
            // 模块设置独立的视图目录
            $tmplPath = APP_PATH . '/' . MODULE_NAME . '/' . C('DEFAULT_V_LAYER') . '/';
        } else {
            $tmplPath = C('VIEW_PATH');
        }

        if ('' == $template) {
            // 如果模板文件名为空 按照默认规则定位
            $template = CONTROLLER_NAME . $depr . ACTION_NAME;
        } elseif (false === strpos($template, $depr)) {
            $template = CONTROLLER_NAME . $depr . $template;
        }
        return $tmplPath . $template . C('TMPL_TEMPLATE_SUFFIX');
    }
}
