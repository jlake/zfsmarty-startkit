<?php
/**
 * 共通コントローラ(管理側)
 * @author ou
 */
class Lib_App_AdminController extends Lib_App_BaseController
{
    /**
     * 初期化
     * @param     boolean  $infoFlg   共通情報取得のフラグ
     * @return void
     */
    public function init($infoFlg = true)
    {
        parent::init($infoFlg);
        if($this->_infoFlg) {
            // ページタイトル先頭文字列指定
            $this->view->headTitle()->setPrefix('My Site Admin');
            $this->view->headTitle()->setSeparator(' - ');
            $this->_appendJs('/js/jquery-1.6.2.min.js');
        }
    }
}
