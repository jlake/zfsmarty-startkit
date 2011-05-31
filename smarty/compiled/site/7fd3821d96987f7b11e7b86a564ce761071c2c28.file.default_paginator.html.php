<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 09:14:10
         compiled from "I:/www/zfsmarty-startkit/application/modules/site/views\partials/default_paginator.html" */ ?>
<?php /*%%SmartyHeaderCode:154584de4b162e177a5-33662430%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7fd3821d96987f7b11e7b86a564ce761071c2c28' => 
    array (
      0 => 'I:/www/zfsmarty-startkit/application/modules/site/views\\partials/default_paginator.html',
      1 => 1306833179,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '154584de4b162e177a5-33662430',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('totalItemCount')->value>1){?>
<p><strong><?php echo $_smarty_tpl->getVariable('firstItemNumber')->value;?>
</strong>件から<strong><?php echo $_smarty_tpl->getVariable('lastItemNumber')->value;?>
</strong>件 （検索結果<strong><?php echo $_smarty_tpl->getVariable('totalItemCount')->value;?>
</strong>件中）</p>
<?php }?>
<?php if ($_smarty_tpl->getVariable('pageCount')->value>1){?>
<div class="pagenation">
    <?php if (isset($_smarty_tpl->getVariable('previous',null,true,false)->value)){?>
      <a href="<?php echo $_smarty_tpl->getVariable('this')->value->baseUrl;?>
page=<?php echo $_smarty_tpl->getVariable('previous')->value;?>
">前ページ</a>
    <?php }else{ ?>
      <span class="disabled">前ページ</span>
    <?php }?>

    <?php  $_smarty_tpl->tpl_vars['page'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('pagesInRange')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['page']->key => $_smarty_tpl->tpl_vars['page']->value){
?>
        <?php if ($_smarty_tpl->tpl_vars['page']->value==$_smarty_tpl->getVariable('current')->value){?>
            <span class="disabled"><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</span>
        <?php }else{ ?>
            <a href="<?php echo $_smarty_tpl->getVariable('this')->value->baseUrl;?>
page=<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</a>
        <?php }?>
    <?php }} ?>

    <?php if (isset($_smarty_tpl->getVariable('next',null,true,false)->value)){?>
      <a href="<?php echo $_smarty_tpl->getVariable('this')->value->baseUrl;?>
page=<?php echo $_smarty_tpl->getVariable('next')->value;?>
">次ページ</a>
    <?php }else{ ?>
      <span class="disabled">次ページ</span>
    <?php }?>
</div>
<?php }?>
