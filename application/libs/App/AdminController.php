<?php
/**
 * 共通コントローラ(管理側)
 * @author ou
 */
class Lib_App_AdminController extends Lib_App_BaseController
{
    const DEFAULT_THEME = 'south-street';

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

    /**
     * jQuery UI を使う (必要な js と css ファイルを追加)
     * @return void
     */
    protected function _useJqueryUI($theme = self::DEFAULT_THEME)
    {
        $this->_appendJs(array(
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js'
        ));
        if(!empty($theme)) {
            $this->_appendCss(array(
                "/css/themes/$theme/jquery-ui-1.8.15.custom.css",
            ));
        }
    }

    /**
     * jqGird を使う (必要な js と css ファイルを追加)
     * @return void
     */
    protected function _useJqGrid($theme = self::DEFAULT_THEME)
    {
        $this->_appendJs(array(
            '/js/jqGrid/js/i18n/grid.locale-ja.js',
            '/js/jqGrid/js/jquery.jqGrid.min.js'
        ));
        $this->_appendCss(array(
            '/js/jqGrid/css/ui.jqgrid.css'
        ));
        if(!empty($theme)) {
            $this->_appendCss(array(
                "/css/themes/$theme/jquery-ui-1.8.15.custom.css",
            ));
        }
    }
}
