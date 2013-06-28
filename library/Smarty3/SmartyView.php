<?php
require_once 'Zend/View/Abstract.php';
require_once 'Smarty3/Smarty.class.php';
/**
 * Smarty用ビュースクリプト
 */
class SmartyView extends Zend_View_Abstract  
{
	/**
	 * Smarty object
	 * @var Smarty
	 */
	protected $_smarty;

	/**
	 * 文字列のエスケープに使う関数
	 * @var string
	 */
	protected $_escape = 'htmlspecialchars';

	/**
	 * コンストラクタ
	 *
	 * @param Smarty $smarty
	 * @return void
	 * 
	 */
	public function __construct(Smarty $smarty = null)
	{

		if(is_null($smarty)){
			$smarty = new Smarty();
		}
		//$smarty->assign('layout', $this->layout());
		$smarty->assignByRef('this', $this);
		$smarty->loadPlugin('smarty_modifier_escape');

		$this->_smarty = $smarty;
	}

	/**
	 * テンプレートエンジンオブジェクトを返します
	 *
	 * @return Smarty
	 */
	public function getEngine()
	{
		return $this->_smarty;
	}

	/**
	 * テンプレートへのパスを設定します
	 *
	 * @param string $path パスとして設定するディレクトリ
	 * @return void
	 */
	public function setScriptPath($path)
	{
		if (is_readable($path)) {
			$this->_smarty->template_dir = $path;
			return;
		}

		throw new Exception("無効なパスが指定されました : '$path'");
	}

	/**
	 * スクリプトパスの追加
	 * @param string $name
	 */
	public function addScriptPath($name)
	{
		$this->setScriptPath($name);
	}

	/**
	 * 現在のテンプレートディレクトリを取得します
	 *
	 * @return string
	 */
	public function getScriptPaths()
	{
		if(is_array($this->_smarty->template_dir)){
			return $this->_smarty->template_dir;
		} else {
			return array($this->_smarty->template_dir);
		}
	}

	/**
	 * setScriptPath へのエイリアス
	 *
	 * @param string $path
	 * @param string $prefix Unused
	 * @return void
	 */
	public function setBasePath($path, $prefix = 'Zend_View')
	{
		return $this->setScriptPath($path);
	}

	/**
	 * setScriptPath へのエイリアス
	 *
	 * @param string $path
	 * @param string $prefix Unused
	 * @return void
	 */
	public function addBasePath($path, $prefix = 'Zend_View')
	{
		return $this->setScriptPath($path);
	}

	/**
	 * Smarty 属性設定
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setParam($key, $value)
	{
		$this->_smarty->$key = $value;
	}

	/**
	 * 変数をテンプレートに代入します
	 *
	 * @param string $key 変数名
	 * @param mixed $val 変数の値
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->_smarty->assign($key, $value);
	}

	/**
	 * 代入された変数を取得します
	 *
	 * @param string $key 変数名
	 * @return mixed 変数の値
	 */
	public function __get($key)
	{
		//return $this->_smarty->get_template_vars($key);
		return $this->_smarty->getTemplateVars($key);
	}

	/**
	 * empty() や isset() のテストが動作するようにします
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function __isset($key)
	{
		//return (null !== $this->_smarty->get_template_vars($key));
		return (isset($this->_smarty->tpl_vars[$key]));
	}

	/**
	 * オブジェクトのプロパティに対して unset() が動作するようにします
	 *
	 * @param string $key
	 * @return void
	 */
	public function __unset($key)
	{
		$this->_smarty->clear_assign($key);
	}

	/**
	 * 変数をテンプレートに代入します
	 *
	 * 指定したキーを指定した値に設定します。あるいは、
	 * キー => 値 形式の配列で一括設定します
	 *
	 * @see __set()
	 * @param string|array $var 使用する代入方式 (キー、あるいは キー => 値 の配列)
	 * @param mixed $value (オプション) 名前を指定して代入する場合は、ここで値を指定します
	 * @return void
	 */
	public function assign($var, $value = null) 
	{ 
		if (is_string($var)) { 
			$this->_smarty->assign($var, $value); 
		} elseif (is_array($var)) { 
			foreach ($var as $key => $value) { 
				$this->assign($key, $value); 
			} 
		} else { 
			throw new Zend_View_Exception('assign() expects a string or array, got '.gettype($var)); 
		} 
		return $this; 
	} 

	/**
	 * 変数をテンプレートに代入します(参照)
	 *
	 * @see assign()
	 * @return void
	 */
	public function assignByRef($spec, $value = null)
	{
		if (is_array($spec)) {
			$this->_smarty->assignByRef($spec);
			return;
		}

		$this->_smarty->assignByRef($spec, $value);
	}

  
	/** 
	 * Zend_View compatibility. Retrieves all template vars 
	 *  
	 * @see Zend_View_Abstract::getVars() 
	 * @return array 
	 */
	public function getVars() 
	{ 
		return $this->_smarty->getTemplateVars(); 
	} 

	/**
	 * 代入済みのすべての変数を削除します
	 *
	 * Zend_View に {@link assign()} やプロパティ
	 * ({@link __get()}/{@link __set()}) で代入された変数をすべて削除します
	 *
	 * @return void
	 */
	public function clearVars()
	{
		$this->_smarty->clearAllAssign(); 
		$this->assign('this', $this); 
		return $this; 
	}

	/**
	 * テンプレートを処理し、結果を出力します
	 * 
	 * @param string $name 処理するテンプレート
	 * @return string 出力結果
	 */
	public function render($name)
	{
		return $this->_smarty->fetch($name);
	}


	/**
	 * プレフィルターのセット
	 *
	 * @param string $filter
	 * @param string $key
	 */
	public function setPreFilter($filter, $key)
	{
		$this->_smarty->autoload_filters['pre'][$key] = $filter;
	}

	/**
	 * ポストフィルターのセット
	 *
	 * @param string $filter
	 * @param string $key
	 */
	public function setPostFilter($filter, $key)
	{
		$this->_smarty->autoload_filters['post'][$key] = $filter;
	}

	/**
	 * アウトプットフィルターのセット
	 * @param string $filter
	 * @param string $key
	 */
	public function setOutputFilter($filter, $key)
	{
		$this->_smarty->autoload_filters['output'][$key] = $filter;
	}


	/**
	 * Escapes a value for output in a view script.
	 *
	 * If escaping mechanism is one of htmlspecialchars or htmlentities, uses
	 * {@link $_encoding} setting.
	 *
	 * @param mixed $var The output to escape.
	 * @return mixed The escaped value.
	 */
	public function escape($var)
	{
		if (in_array($this->_escape, array('htmlspecialchars', 'htmlentities'))) {
			return call_user_func($this->_escape, $var, ENT_COMPAT);
		}

		return call_user_func($this->_escape, $var);
	}

	/**
	 * Accesses a helper object from within a script.
	 *
	 * If the helper class has a 'view' property, sets it with the current view
	 * object.
	 *
	 * @param string $name The helper name.
	 * @param array $args The parameters for the helper.
	 * @return string The result of the helper output.
	 */
	public function __call($name, $args)
	{
		// is the helper already loaded?
		$helper = $this->getHelper($name);

		// call the helper method
		return call_user_func_array(
			array($helper, $name),
			$args
		);
	}

	/**
	 * Smarty オブジェクトをクローンする
	 *
	 * @return void
	 */
	public function __clone()
	{
		$this->_smarty = clone $this->_smarty;
	}

	/**
	 * Includes the view script in a scope with only public $this variables.
	 * @param string The view script to execute.
	 */
	protected function _run()
	{
		include func_get_arg(0);
	}
}
?>