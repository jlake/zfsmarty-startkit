<?php
/**
 * api module
 * Index コントローラ
 * @author ou
 */
class Api_IndexController extends Lib_App_ApiController
{
	/**
	 * Stores the XML output in DOMDocument format
	 * 
	 * @var DOMDocument
	 */
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
    }

    public function jsontestAction()
    {
        $arr = array(
            'name' => '花子',
            'age' => 18,
            'sex' => '女'
        );
        $this->_sendJson($arr);
    }

    public function xmltestAction()
    {
        $this->_setLayout('xml');
        $this->getResponse()
            ->setHeader('Content-Type', 'text/xml; charset=UTF-8', true);
        $this->view->item = array(
            'name' => '花子',
            'age' => 18,
            'sex' => '女'
        );
    }
}
