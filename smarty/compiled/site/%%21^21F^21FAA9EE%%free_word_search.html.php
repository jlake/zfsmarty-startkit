<?php /* Smarty version 2.6.26, created on 2011-05-31 09:14:27
         compiled from ../partials/free_word_search.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', '../partials/free_word_search.html', 3, false),)), $this); ?>
<div id="search-block">
    <form action="/search/list" method="post" accept-charset="utf-8">
        <div><input type="text" id="keyword" name="keyword" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['this']->params['keyword'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="16"></div>
        <div><input type="submit" id="submit_search" name="submit_search" value="検索"></div>
    </form>
</div>