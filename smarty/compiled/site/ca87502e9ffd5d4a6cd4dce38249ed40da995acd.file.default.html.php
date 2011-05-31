<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:47:54
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/layouts/default.html" */ ?>
<?php /*%%SmartyHeaderCode:311484de4ab3a6ac692-68603858%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ca87502e9ffd5d4a6cd4dce38249ed40da995acd' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/layouts/default.html',
      1 => 1306827259,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '311484de4ab3a6ac692-68603858',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php echo '<?xml';?> version="1.0" encoding="utf-8"<?php echo '?>';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php echo $_smarty_tpl->getVariable('this')->value->headMeta();?>

    <?php echo $_smarty_tpl->getVariable('this')->value->headTitle();?>

    <link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">
    <!--[if IE]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <link rel="stylesheet" href="/css/blueprint/plugins/link-icons/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection">
    <?php echo $_smarty_tpl->getVariable('this')->value->headLink();?>

    <script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
    <?php echo $_smarty_tpl->getVariable('this')->value->headScript();?>

</head>
<body class="reset">
<div id="wrap" class="container">
    <div id="header" class="span-24 last">
        <a href="/"><img src="/images/header.gif" alt="サイトLogo画像" /></a>
        <?php $_template = new Smarty_Internal_Template('../partials/free_word_search.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    </div>
    <div id="left_sidebar" class="span-5 sidebar">
        <div id="top-block" class="box">
            ・<a href="/" title="TOPページ">TOPページ</a>
        </div>
        <?php $_template = new Smarty_Internal_Template('../partials/sidebar_user_info.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
        <?php $_template = new Smarty_Internal_Template('../partials/sidebar_my_account.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
        <?php $_template = new Smarty_Internal_Template('../partials/sidebar_site_links.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
    </div>
    <div id="content" class="span-18 last">
        <?php if ($_smarty_tpl->getVariable('this')->value->flashMsg){?>
            <div id="flash_msg" class="info"><?php echo $_smarty_tpl->getVariable('this')->value->flashMsg;?>
</div>
        <?php }?>
        <?php $_smarty_tpl->tpl_vars['layout'] = new Smarty_variable($_smarty_tpl->getVariable('this')->value->layout(), null, null);?>
        <?php echo $_smarty_tpl->getVariable('layout')->value->content;?>

    </div>
    <div id="footer" class="clear span-24 last">
        <div id="copyright">Copyright(c) Company Name.</div>
    </div>
</div>
</body>
</html>

