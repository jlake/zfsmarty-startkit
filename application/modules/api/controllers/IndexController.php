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
        $arr = array(
            'name' => '花子<abc>',
            'age' => 18,
            'sex' => '女'
        );
        $this->_sendXml($arr);
    }
}
