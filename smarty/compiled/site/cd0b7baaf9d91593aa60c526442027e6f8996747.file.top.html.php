<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:59:27
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/scripts/dummy/top.html" */ ?>
<?php /*%%SmartyHeaderCode:104004de4adefc796c3-63525367%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cd0b7baaf9d91593aa60c526442027e6f8996747' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/scripts/dummy/top.html',
      1 => 1306830673,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '104004de4adefc796c3-63525367',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_escape')) include 'I:\www\zfsmarty-startkit\library\Smarty3\plugins\modifier.escape.php';
?><?php $_template = new Smarty_Internal_Template('scripts/dummy/topmsg_dummy.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
<p>サンプル用Dummyデータです。</p>
<?php echo $_smarty_tpl->getVariable('paginator')->value;?>

<table>
    <tr>
        <th>カラム1</th>
        <th>カラム2</th>
        <th>操作</th>
    </tr>
<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('paginator')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value){
 $_smarty_tpl->tpl_vars['i']->value = $_smarty_tpl->tpl_vars['row']->key;
?>
    <tr>
        <td><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('row')->value->inf1);?>
</td>
        <td><?php echo smarty_modifier_escape($_smarty_tpl->getVariable('row')->value->inf2);?>
</td>
        <td>
        <a href="/dummy/edit/id/<?php echo $_smarty_tpl->getVariable('row')->value->id;?>
/page/<?php echo $_smarty_tpl->getVariable('paginator')->value->current;?>
">変更</a>&nbsp;
        <a href="/dummy/delete/id/<?php echo $_smarty_tpl->getVariable('row')->value->id;?>
/page/<?php echo $_smarty_tpl->getVariable('paginator')->value->current;?>
">削除</a>
        </td>
    </tr>
<?php }} ?>
</table>
<a href="/dummy/edit/page/last">追加</a>
<br />
<br />
<?php echo $_smarty_tpl->getVariable('paginator')->value;?>

