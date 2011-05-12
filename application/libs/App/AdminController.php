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
        $this->view->headTitle('管理');
    }
}
