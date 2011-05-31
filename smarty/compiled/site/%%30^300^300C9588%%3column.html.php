<?php /* Smarty version 2.6.26, created on 2011-05-31 09:14:27
         compiled from 3column.html */ ?>
<?php echo '<?xml'; ?>
 version="1.0" encoding="utf-8"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php echo $this->_tpl_vars['this']->headMeta(); ?>

    <?php echo $this->_tpl_vars['this']->headTitle(); ?>

    <link rel="stylesheet" href="/css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/blueprint/print.css" type="text/css" media="print">
    <!--[if IE]><link rel="stylesheet" href="/css/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->
    <link rel="stylesheet" href="/css/blueprint/plugins/link-icons/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection">
    <?php echo $this->_tpl_vars['this']->headLink(); ?>

    <script type="text/javascript" src="/js/jquery-1.4.4.min.js"></script>
    <?php echo $this->_tpl_vars['this']->headScript(); ?>

</head>
<body class="reset">
<div id="wrap" class="container">
    <div id="header" class="span-24 last">
        <a href="/"><img src="/images/header.gif" alt="サイトLogo画像" /></a>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../partials/free_word_search.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="left_sidebar" class="span-5 sidebar">
        <div id="top-block" class="box">
            ・<a href="/" title="TOPページ">TOPページ</a>
        </div>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../partials/sidebar_user_info.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../partials/sidebar_my_account.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../partials/sidebar_site_links.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div id="content" class="span-13">
        <?php if (isset ( $this->_tpl_vars['flashMsg'] )): ?>
            <div id="flash_msg" class="info"><?php echo $this->_tpl_vars['flashMsg']; ?>
</div>
        <?php endif; ?>
        <?php $this->assign('layout', $this->_tpl_vars['this']->layout()); ?>
        <?php echo $this->_tpl_vars['layout']->content; ?>

    </div>
    <div id="rignt_sidebar" class="span-5 sidebar last">
        <?php if ($this->_tpl_vars['this']->params['controller'] == 'dummy'): ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../scripts/dummy/sidebar_dummy.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php elseif ($this->_tpl_vars['this']->params['controller'] == 'contentsdetails' && $this->_tpl_vars['this']->params['action'] == 'detail'): ?>
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => '../partials/sidebar_cart_box.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    </div>
    <div id="footer" class="clear span-24 last">
        <div id="copyright">Copyright(c) Company Name.</div>
    </div>
</div>
</body>
</html>
