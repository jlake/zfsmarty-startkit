<?php
/**
 * Site module
 * エラー処理 コントローラ
 * @author ou
 */
class ErrorController extends Zend_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_helper->layout()->setLayout('error');
    }

    /**
     * エラーメッセージ画面
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = '指定ページ見つかりません';
                $this->render('404');
                return;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'アプリケーションエラー';
                break;
        }
        $exceptionMsg = $errors->exception->getMessage();
        $stackTrace = $errors->exception->getTraceAsString();
        $this->view->exceptionMsg = $exceptionMsg;
        if(APPLICATION_ENV != 'development') {
            $this->view->debugFlg = false;
        } else {
            $this->view->debugFlg = true;
            $this->view->stackTrace = $stackTrace;
            $this->view->request  = print_r($errors->request, true);

            $db = Zend_Registry::get('db');
            $profiler = $db->getProfiler();
            $query = $profiler->getLastQueryProfile();
            if($query) {
                $this->view->lastSql = $query->getQuery();
            }
        }

        $this->_writeTraceLog(
            "Exception Message:\n".$exceptionMsg.
            "\nStack Trace:\n".$stackTrace
        );
    }

    /**
     * システムエラーメッセージ画面（共通）
     */
    public function syserrorAction()
    {
    }

    /**
     * 404ページ
     */
    public function page404Action()
    {
        $this->_show404Page();
    }

    /**
     * 404ページの表示
     */
    private function _show404Page()
    {
        //$this->_helper->layout()->setLayout('default');
        $this->getResponse()->setHttpResponseCode(404);
        $this->render('404');
    }

    /**
     * スタックトレースログを書き出す
     *
     * @param $logMsg ログ内容
     * @return  unknown
     */
    private function _writeTraceLog($logMsg)
    {
        $logger = Zend_Registry::get('trace_logger');
        return $logger->log($logMsg, Zend_Log::ERR);
    }
}

