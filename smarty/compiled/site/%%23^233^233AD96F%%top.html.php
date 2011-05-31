<?php /* Smarty version 2.6.26, created on 2011-05-31 09:14:27
         compiled from I:%5Cwww%5Czfsmarty-startkit/application/modules/site/views/scripts/dummy/top.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'I:\\www\\zfsmarty-startkit/application/modules/site/views/scripts/dummy/top.html', 12, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'scripts/dummy/topmsg_dummy.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<p>サンプル用Dummyデータです。</p>
<?php echo $this->_tpl_vars['paginator']; ?>

<table>
    <tr>
        <th>カラム1</th>
        <th>カラム2</th>
        <th>操作</th>
    </tr>
<?php $_from = $this->_tpl_vars['paginator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['row']):
?>
    <tr>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']->inf1)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['row']->inf2)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
        <td>
        <a href="/dummy/edit/id/<?php echo $this->_tpl_vars['row']->id; ?>
/page/<?php echo $this->_tpl_vars['paginator']->current; ?>
">変更</a>&nbsp;
        <a href="/dummy/delete/id/<?php echo $this->_tpl_vars['row']->id; ?>
/page/<?php echo $this->_tpl_vars['paginator']->current; ?>
">削除</a>
        </td>
    </tr>
<?php endforeach; endif; unset($_from); ?>
</table>
<a href="/dummy/edit/page/last">追加</a>
<br />
<br />
<?php echo $this->_tpl_vars['paginator']; ?>
