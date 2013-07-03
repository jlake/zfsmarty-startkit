<?php
/**
 * 共通コントローラ(管理側)
 * @author ou
 */
class Lib_App_AdminController extends Lib_App_BaseController
{
    const DEFAULT_THEME = 'redmond';

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
        }
    }

    /**
     * jQuery UI を使う (必要な js と css ファイルを追加)
     * @return void
     */
    protected function _useJqueryUI($theme = self::DEFAULT_THEME)
    {
        $this->_appendJs(array(
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js'
        ));
        if(!empty($theme)) {
            $this->_appendCss(array(
                REWRITE_BASE . "/css/jqThemes/$theme/jquery-ui-1.10.3.custom.css",
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
            REWRITE_BASE . '/js/vendor/jqGrid/js/i18n/grid.locale-ja.js',
            REWRITE_BASE . '/js/vendor/jqGrid/js/jquery.jqGrid.min.js'
        ));
        $this->_appendCss(array(
            REWRITE_BASE . '/js/vendor/jqGrid/css/ui.jqgrid.css'
        ));
        if(!empty($theme)) {
            $this->_appendCss(array(
                REWRITE_BASE . "/css/jqThemes/$theme/jquery-ui-1.10.3.custom.css",
            ));
        }
    }
}
