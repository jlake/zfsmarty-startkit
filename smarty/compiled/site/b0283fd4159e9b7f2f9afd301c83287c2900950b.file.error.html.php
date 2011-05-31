<?php /* Smarty version Smarty-3.0.7, created on 2011-05-31 08:48:08
         compiled from "I:\www\zfsmarty-startkit/application/modules/site/views/scripts/error/error.html" */ ?>
<?php /*%%SmartyHeaderCode:268384de4ab488cd181-66395839%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b0283fd4159e9b7f2f9afd301c83287c2900950b' => 
    array (
      0 => 'I:\\www\\zfsmarty-startkit/application/modules/site/views/scripts/error/error.html',
      1 => 1283757822,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '268384de4ab488cd181-66395839',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<h1>エラーが発生</h1>
<h2><?php echo $_smarty_tpl->getVariable('this')->value->message;?>
</h2>
<h3>Exception 情報:</h3>
<p>
    <b>メッセージ</b>
    <pre><?php echo $_smarty_tpl->getVariable('this')->value->exceptionMsg;?>
</pre>
</p>
<?php if ($_smarty_tpl->getVariable('this')->value->debugFlg){?>
    <p>
        <b>スタックトレース</b>
        <pre><?php echo $_smarty_tpl->getVariable('this')->value->stackTrace;?>
</pre>
    </p>

    <h3>リクエスト:</h3>
        <pre><?php echo $_smarty_tpl->getVariable('this')->value->request;?>

    </pre>

    <h3>クエリ情報:</h3>
        <pre><?php echo $_smarty_tpl->getVariable('this')->value->lastSql;?>

    </pre>
<?php }?>
<br />