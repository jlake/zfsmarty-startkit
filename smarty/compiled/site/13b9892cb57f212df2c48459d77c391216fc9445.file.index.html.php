<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:48:08
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/scripts/index/index.html" */ ?>
<?php /*%%SmartyHeaderCode:299374de4ab487d84d9-95779450%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '13b9892cb57f212df2c48459d77c391216fc9445' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/scripts/index/index.html',
      1 => 1306831686,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '299374de4ab487d84d9-95779450',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="top-box" class="box">
    <strong>TOPページ</strong>
</div>
<div>
    ・<a href="/dummy/">Dummy</a><br />
    フレームワークの使い方、サンプルです。<br />
    <br />
    ・<a href="/cart/">買い物カゴ</a><br />
    作成中...<br />
    <br />
    <br />
    <a href="/admin/">管理画面へ</a><br />
</div>
<?php echo $_smarty_tpl->getVariable('this')->value->friends();?>
