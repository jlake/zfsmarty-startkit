<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:47:54
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/layouts/../partials/free_word_search.html" */ ?>
<?php /*%%SmartyHeaderCode:262294de4ab3a7e05f7-32038491%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f3f075bb4baf8ecfda4b4105ec2b18b1c6ab1d1f' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/layouts/../partials/free_word_search.html',
      1 => 1306827143,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '262294de4ab3a7e05f7-32038491',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_escape')) include 'I:\www\zfsmarty-startkit\library\Smarty3\plugins\modifier.escape.php';
?><div id="search-block">
    <form action="/search/list" method="post" accept-charset="utf-8">
        <div><input type="text" id="keyword" name="keyword" value="<?php echo smarty_modifier_escape($_smarty_tpl->getVariable('this')->value->params['keyword']);?>
" size="16"></div>
        <div><input type="submit" id="submit_search" name="submit_search" value="検索"></div>
    </form>
</div>
