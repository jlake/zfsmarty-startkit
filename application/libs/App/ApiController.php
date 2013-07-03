<?php
/**
 * 共通コントローラ(API側)
 * @author ou
 */
class Lib_App_ApiController extends Lib_App_BaseController
{
    /**
     * 初期化
     * @param     boolean  $infoFlg   共通情報取得のフラグ
     * @return void
     */
    public function init($infoFlg = true)
    {
        parent::init($infoFlg);
    }
}
