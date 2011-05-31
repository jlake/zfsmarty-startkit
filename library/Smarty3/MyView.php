<?php
defined('SMARTY_VERSION') || define('SMARTY_VERSION', 3);
 
/**
 * @see Smarty
 */
require_once 'Smarty' . SMARTY_VERSION . '/Smarty.class.php';
 
/**
 * @see Zend_View_Abstract
 */
require_once 'Zend/View/Abstract.php';
 
/**
 * 處理樣版
 *
 */
class My_View_Smarty extends Zend_View_Abstract
{
    /**
     * Smarty 物件
     *
     * @var Smarty
     */
    protected $_smarty = null;
 
    /**
     * 建構子
     *
     * 處理 Smarty 相關設定
     *
     * @param array $config Configuration data
     */
    public function __construct($config = array())
    {
        parent::__construct($config);
 
        $this->_smarty = new Smarty();
 
        if (array_key_exists('params', $config)) {
            foreach ($config['params'] as $key => $value) {
                if ('plugins_dir' === $key) {
                    $this->_addtPluginDir($value);
                } else {
                    if (property_exists('Smarty', $key)) {
                        $this->_smarty->$key = $value;
                    }
                }
            }
        }
 
        $this->_addtPluginDir(dirname(__FILE__) . '/Smarty/plugins');
        $this->_smarty->assign('this', $this);
    }
 
    /**
     * 新增 Plugin 搜尋路徑
     *
     * @param string $pluginPath 
     */
    protected function _addtPluginDir($pluginPath)
    {
        if (3 === SMARTY_VERSION) {
            $this->_smarty->addPluginsDir($pluginPath);
        } else {
            $this->_smarty->plugins_dir[] = $pluginPath;
        }
    }
 
    /**
     * 回傳 Smarty 物件
     *
     * @return Smarty
     */
    public function getEngine()
    {
        return $this->_smarty;
    }
 
    /**
     * 設定 Smarty 屬性
     *
     * @param string $key
     * @param mixed $value
     */
    public function setParam($key, $value)
    {
        $this->_smarty->$key = $value;
    }
 
    /**
     * 設定樣版變數
     *
     * @param string $key 樣版變數名稱
     * @param mixed $value 樣版變數值
     */
    public function __set($key, $value)
    {
        parent::__set($key, $value);
        $this->_smarty->assign($key, $value);
    }
 
    /**
     * 取得樣版變數
     *
     * @param string $key 樣版變數名稱
     * @return mixed 樣版變數值
     */
    public function __get($key)
    {
        return $this->_smarty->getTemplateVars($key);
    }
 
    /**
     * 檢查樣版變數是否存在
     *
     * @param string $key 樣版變數名稱
     * @return boolean
     */
    public function __isset($key)
    {
        return null === $this->_smarty->getTemplateVars($key);
    }
 
    /**
     * 移除樣版變數
     *
     * @param string $key 樣版變數名稱
     */
    public function __unset($key)
    {
        parent::__unset($key);
        $this->_smarty->clearAssign($key);
    }
 
    /**
     * 設定樣版變數
     *
     * @param string | array $var 樣版變數名稱或樣版變數陣列 (key => value)
     * @param mixed $value 樣版變數值
     */
    public function assign($var, $value = null)
    {
        if (is_array($var)) {
            foreach ($var as $key => $value) {
                parent::__set($key, $value);
            }
            $this->_smarty->assign($var);
            return;
        }
 
        $this->__set($var, $value);
    }
 
    /**
     * 移除所有樣版變數
     */
    public function clearVars()
    {
        parent::clearVars();
        $this->_smarty->clearAllAssign();
    }
 
    /**
     * Extension of the abstract parent class method
     */
    protected function _run()
    {
        $oldErrorHandler = set_error_handler(array($this, 'emptyErrorHandler'));
        $file = func_get_arg(0);
        echo $this->_smarty->fetch($file);
        restore_error_handler();
    }
 
    /**
     *
     */
    public function emptyErrorHandler()
    {
    }
}