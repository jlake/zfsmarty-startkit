<?php
/**
 * 開発サポート
 * サンプル バッチ
 * @author ou
 */
require_once dirname(__FILE__) . '/../CliBootstrap.php';

class Batch_Sample
{
    protected $_opts;
    protected $_db;
    protected $_dbw;
    protected $_logger;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
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
                    'force|f' => 'Force execute.',
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
     * 実行
     */
    public function run()
    {
        global $argv;
        $this->_init();
        $this->_logger->log(' -- START run ' . __FILE__, Zend_Log::INFO);
        $this->_logger->log('  options: ' . print_r($this->_opts->getOptions(), true), Zend_Log::INFO);

        //test 1
        $row = $this->_db->fetchRow('SELECT * FROM dummy');
        print_r($row);

        //test 2
        $dummyWrite = new Lib_Db_Table_Dummy($this->_dbw);
        $where = $dummyWrite->getAdapter()->quoteInto('id = ?', 1);
        $data = array(
            'inf1' => 'Test1',
            'inf2' => 'Test2'
        );
        $dummyWrite->update($data, $where);

        //test 3
        $dummyRead = new Lib_Db_Table_Dummy($this->_db);
        $row = $dummyRead->find(1)->toArray();
        print_r($row);


        //test 4
        $dataObj = Lib_Db_Util::getDataObject('dummy');
        $row = $dataObj->getRow(array('id' => 1));
        print_r($row);

        $this->_logger->log(' -- END run ' . __FILE__, Zend_Log::INFO);
    }
}

$batch = new Batch_Sample();
$batch->run();

