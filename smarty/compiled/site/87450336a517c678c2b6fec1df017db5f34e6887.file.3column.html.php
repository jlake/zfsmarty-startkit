<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:59:27
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/layouts/3column.html" */ ?>
<?php /*%%SmartyHeaderCode:304de4adefde3103-28057737%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '87450336a517c678c2b6fec1df017db5f34e6887' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/layouts/3column.html',
      1 => 1306827614,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '304de4adefde3103-28057737',
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
    <div id="content" class="span-13">
        <?php if (isset($_smarty_tpl->getVariable('flashMsg',null,true,false)->value)){?>
            <div id="flash_msg" class="info"><?php echo $_smarty_tpl->getVariable('flashMsg')->value;?>
</div>
        <?php }?>
        <?php $_smarty_tpl->tpl_vars['layout'] = new Smarty_variable($_smarty_tpl->getVariable('this')->value->layout(), null, null);?>
        <?php echo $_smarty_tpl->getVariable('layout')->value->content;?>

    </div>
    <div id="rignt_sidebar" class="span-5 sidebar last">
        <?php if ($_smarty_tpl->getVariable('this')->value->params['controller']=='dummy'){?>
            <?php $_template = new Smarty_Internal_Template('../scripts/dummy/sidebar_dummy.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
        <?php }elseif($_smarty_tpl->getVariable('this')->value->params['controller']=='contentsdetails'&&$_smarty_tpl->getVariable('this')->value->params['action']=='detail'){?>
            <?php $_template = new Smarty_Internal_Template('../partials/sidebar_cart_box.html', $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php unset($_template);?>
        <?php }?>
    </div>
    <div id="footer" class="clear span-24 last">
        <div id="copyright">Copyright(c) Company Name.</div>
    </div>
</div>
</body>
</html>

