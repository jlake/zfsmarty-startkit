<?php
/**
 * Site module
 * 画像処理 コントローラ
 * @author ou
 */
class ImageController extends Lib_App_SiteController
{

    public function init()
    {
        parent::init(false); // 共通情報取得しない
        $this->_disableLayout(true);
    }

    public function indexAction()
    {
        $this->_forward('contents');
    }

    public function contentsAction()
    {
        Lib_Util_Image::displayImage(ROOT_PATH . '/data/contents/' . $this->_params['src'], $this->_params['w'], $this->_params['h']);
    }

    public function uploadsAction()
    {
        Lib_Util_Image::displayImage(ROOT_PATH . '/data/uploads/' . $this->_params['src'], $this->_params['w'], $this->_params['h']);
    }

}
