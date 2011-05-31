<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:47:54
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/layouts/../partials/sidebar_user_info.html" */ ?>
<?php /*%%SmartyHeaderCode:302184de4ab3a8188c4-52490445%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0da250c5eb243ca0be62ac78ae716cb2e6a4578a' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/layouts/../partials/sidebar_user_info.html',
      1 => 1291021650,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '302184de4ab3a8188c4-52490445',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<div id="user-info" class="box">
    <label>ユーザ情報</label><br />
<?php if ($_smarty_tpl->getVariable('this')->value->userInfo&&$_smarty_tpl->getVariable('this')->value->userInfo['user_id']){?>
    <form action="/auth/logout" method="post" accept-charset="utf-8">
        <div><label for="user_id">ユーザID：</label>
            <?php echo $_smarty_tpl->getVariable('this')->value->userInfo['user_id'];?>

        </div>
        <div><input type="submit" name="logout_submit" value="ログアウト" id="logout_submit"></div>
    </form>
<?php }else{ ?>
    <ul>
        <li><a href="/auth/login" title="">ログイン</a></li>
    </ul>
<?php }?>
</div>
