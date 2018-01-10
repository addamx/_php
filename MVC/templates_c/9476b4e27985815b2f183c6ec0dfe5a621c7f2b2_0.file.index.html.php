<?php
/* Smarty version 3.1.30, created on 2016-08-20 08:29:26
  from "F:\Dropbox\WorkPlace\WWW\MVC\App\Home\View\Index\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_57b7a466db0602_70647145',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9476b4e27985815b2f183c6ec0dfe5a621c7f2b2' => 
    array (
      0 => 'F:\\Dropbox\\WorkPlace\\WWW\\MVC\\App\\Home\\View\\Index\\index.html',
      1 => 1471652965,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57b7a466db0602_70647145 (Smarty_Internal_Template $_smarty_tpl) {
?>
<html>
<head>
	
</head>
<body>
<h1><?php echo $_smarty_tpl->tpl_vars['a']->value;?>
</h1>
<form action="<?php echo @constant('__FILE__');?>
" method="post">
	<p><input name="user" type="text" placeholder="user"/></p>
	<p><input name="password" type="text" placeholder="password" /></p>
	<p><input name="salt" type="text" placeholder="salt"/></p>
	<input name='id' type='text' value='5' hidden />
	<button type="submit" >提交</button>
	<a href="<?php echo @constant('__MODULE__');?>
"><?php echo @constant('__MODULE__');?>
</a>
	<a href="<?php echo @constant('__CONTROLLER__');?>
"><?php echo @constant('__CONTROLLER__');?>
</a>
	<a href="<?php echo @constant('__ACTION__');?>
"><?php echo @constant('__ACTION__');?>
</a>
</form>
</body>
</html><?php }
}
