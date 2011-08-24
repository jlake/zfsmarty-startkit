<?php
/**
 * 開発サポート
 * テーブルクラス(Zend_Db_Tableの拡張)作成 バッチ
 * @author ou
 */
require_once '../CliBootstrap.php';

class Batch_MakeTableClasses
{
    protected $_opts;
    protected $_db;
    protected $_dbw;
    protected $_logger;
    protected $_rootPath;

    /**
     * コンストラクタ
     */
    public function __construct($rootPath)
    {
        $this->_rootPath = $rootPath;
    }

    /**
     * 初期化
     */
    protected function _init()
    {
        try {
            $this->_opts = new Zend_Console_Getopt(
                array(
                    'help|h' => 'Displays usage information.',
                    'force|f' => 'Force overwrite.',
                )
            );
            $this->_opts->parse();
        } catch (Zend_Console_Getopt_Exception $e) {
            exit($e->getMessage() ."\n\n". $e->getUsageMessage());
        }

        if(isset($this->_opts->help)) {
            echo $this->_opts->getUsageMessage();
            exit;
        }

        $this->_db = Zend_Registry::get('db');
        $this->_dbw = Zend_Registry::get('dbw');
        $this->_logger = Zend_Registry::get('batch_logger');
    }

    /**
     * テーブル名からクラス名の取得
     */
    public static function _getClassName($tableName)
    {
        $words = explode('_', $tableName);
        foreach($words as &$word) {
            $word = ucfirst($word);
        }
        return implode('', $words);
    }

    /** 
    * DbTable クラスファイルの作成
    */
    private function _makeClassFile($tableName) {
        $className = $this->_getClassName($tableName);
        $filePath = $this->_rootPath.'/'.$className.'.php';
        if(!isset($this->_opts->force) && file_exists($filePath)) {
            return;
        }

        echo "$tableName --> $filePath \n";

        $template = "<?php
class Lib_Db_Table_%className% extends Lib_Db_Table
{
    protected \$_name = '%tableName%';
    protected \$_primary = %primaryKeys%;
}
";

        $pkeys = $this->_db->fetchCol("SELECT `COLUMN_NAME`
            FROM `information_schema`.`COLUMNS`
            WHERE (`TABLE_NAME` = '$tableName')
                AND (`COLUMN_KEY` = 'PRI')
        ");
        $primaryKeys = (count($pkeys) > 0) ? "array('".implode("', '", $pkeys)."')" : 'null';

        $contents = Lib_Util_Message::replace($template, array(
                'className' => $className,
                'tableName' => $tableName,
                'primaryKeys' => $primaryKeys
            )
        );
        $contents = mb_convert_encoding($contents, 'UTF-8', 'auto');
        try {
            if(file_exists($filePath) && !is_writable($filePath)) {
                echo "Could not overwrite $filePath \n";
                return;
            }
            file_put_contents($filePath, $contents);
        } catch(Exception $e) {
            echo "Failed to write file $filePath \n";
        }
    }

    /**
     * 実行
     */
    public function run()
    {
        $this->_init();
        $this->_logger->log('START run ' . __FILE__, Zend_Log::INFO);

        if(is_writable($this->_rootPath)) {
            $tableNames = $this->_db->fetchCol("SHOW TABLES");
            foreach($tableNames as $tableName) {
                if(preg_match('/_(tbl|mst)$/i', $tableName)) {
                    $this->_makeClassFile($tableName);
                }
            }
        } else {
            echo $this->_rootPath . " is not writable.\n";
        }

        $this->_logger->log('END run ' . __FILE__, Zend_Log::INFO);
    }
}

$batch = new Batch_MakeTableClasses( APPLICATION_PATH.'/libs/Db/Table' );
$batch->run();

