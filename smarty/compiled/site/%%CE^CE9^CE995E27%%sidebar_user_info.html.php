<?php /* Smarty version 2.6.26, created on 2011-05-31 09:14:27
         compiled from ../partials/sidebar_user_info.html */ ?>
<div id="user-info" class="box">
    <label>ユーザ情報</label><br />
<?php if ($this->_tpl_vars['this']->userInfo && $this->_tpl_vars['this']->userInfo['user_id']): ?>
    <form action="/auth/logout" method="post" accept-charset="utf-8">
        <div><label for="user_id">ユーザID：</label>
            <?php echo $this->_tpl_vars['this']->userInfo['user_id']; ?>

        </div>
        <div><input type="submit" name="logout_submit" value="ログアウト" id="logout_submit"></div>
    </form>
<?php else: ?>
    <ul>
        <li><a href="/auth/login" title="">ログイン</a></li>
    </ul>
<?php endif; ?>
</div>