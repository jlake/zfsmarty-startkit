<?php /* Smarty version 2.6.26, created on 2011-05-31 09:14:27
         compiled from partials/default_paginator.html */ ?>
<?php if ($this->_tpl_vars['totalItemCount'] > 1): ?>
<p><strong><?php echo $this->_tpl_vars['firstItemNumber']; ?>
</strong>件から<strong><?php echo $this->_tpl_vars['lastItemNumber']; ?>
</strong>件 （検索結果<strong><?php echo $this->_tpl_vars['totalItemCount']; ?>
</strong>件中）</p>
<?php endif; ?>
<?php if ($this->_tpl_vars['pageCount'] > 1): ?>
<div class="pagenation">
    <?php if (isset ( $this->_tpl_vars['previous'] )): ?>
      <a href="<?php echo $this->_tpl_vars['this']->baseUrl; ?>
page=<?php echo $this->_tpl_vars['previous']; ?>
">前ページ</a>
    <?php else: ?>
      <span class="disabled">前ページ</span>
    <?php endif; ?>

    <?php $_from = $this->_tpl_vars['pagesInRange']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
        <?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['current']): ?>
            <span class="disabled"><?php echo $this->_tpl_vars['page']; ?>
</span>
        <?php else: ?>
            <a href="<?php echo $this->_tpl_vars['this']->baseUrl; ?>
page=<?php echo $this->_tpl_vars['page']; ?>
"><?php echo $this->_tpl_vars['page']; ?>
</a>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>

    <?php if (isset ( $this->_tpl_vars['next'] )): ?>
      <a href="<?php echo $this->_tpl_vars['this']->baseUrl; ?>
page=<?php echo $this->_tpl_vars['next']; ?>
">次ページ</a>
    <?php else: ?>
      <span class="disabled">次ページ</span>
    <?php endif; ?>
</div>
<?php endif; ?>