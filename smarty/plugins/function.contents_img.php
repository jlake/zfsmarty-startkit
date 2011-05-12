<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.contents_img.php
 * Type:     function
 * Name:     contents_img
 * Purpose:  ファイル名指定でコンテンツ画像を表示する
 * Example:  {contents_img file=samples/sample01.jpg [w=100] [h=100]}
 * -------------------------------------------------------------
 */
function smarty_function_contents_img($params, &$smarty)
{
    if (empty($params['src'])) return '<!-- 画像ファイルを指定してください -->';
    $src = '/image/contents?src='.$params['src'];
    if($params['w']) {
        $src .= '&w=' . $params['w'];
    }
    if($params['h']) {
        $src .= '&h=' . $params['h'];
    }
    $attrs = '';
    if($params['class']) {
        $attrs = ' class="' . $params['class'] . '"';
    }
    if($params['style']) {
        $attrs = ' style="' . $params['style'] . '"';
    }
    return '<img src="'.$src.'"'.$attrs.' />';
}