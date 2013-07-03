<?php
/**
 * ビューヘルパー - Partial
 * @author ou
 *
 */
class Lib_View_Helper_Partial extends Zend_View_Helper_Partial {
    /**
     * ビューをクロンする
     *
     * @param   なし
     * @return  object
     */
    public function cloneView()
    {
        $view = parent::cloneView();
        $view->assign('this', $this->view);
        
        return $view;
    }
}