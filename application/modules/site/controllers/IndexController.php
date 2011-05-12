<?php
/**
 * Site module
 * Index コントローラ
 * @author ou
 */
class IndexController extends Lib_App_SiteController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    }

    public function topAction()
    {
        $this->_forward('index');
    }
}
