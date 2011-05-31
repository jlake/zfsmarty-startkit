<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:59:27
         compiled from "I:/www/zfsmarty-startkit/application/modules/site/views\scripts/dummy/topmsg_dummy.html" */ ?>
<?php /*%%SmartyHeaderCode:154494de4adefd63743-26245943%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1f5a6b35dadb9e355f653300f79df7fd554a5ea4' => 
    array (
      0 => 'I:/www/zfsmarty-startkit/application/modules/site/views\\scripts/dummy/topmsg_dummy.html',
      1 => 1284524918,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '154494de4adefd63743-26245943',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="dummy-box" class="box">
    モジュール: <?php echo $_smarty_tpl->getVariable('this')->value->params['module'];?>
 &nbsp;&nbsp;
    コントローラー: <?php echo $_smarty_tpl->getVariable('this')->value->params['controller'];?>
 &nbsp;&nbsp;
    アクション: <?php echo $_smarty_tpl->getVariable('this')->value->params['action'];?>
 <br />
</div>
