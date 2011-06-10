<?php
require_once 'CliBootstrap.php';

class Batch_Sample
{
    protected $_db;
    protected $_logger;

    public function __construct()
    {
    }

    protected function _init() {
        try {
            $opts = new Zend_Console_Getopt(
                array(
                    'help|h' => 'Displays usage information.',
                )
            );
            $opts->parse();
        } catch (Zend_Console_Getopt_Exception $e) {
            exit($e->getMessage() ."\n\n". $e->getUsageMessage());
        }

        if(isset($opts->h)) {
            echo $opts->getUsageMessage();
            exit;
        }

        $this->_db = Zend_Registry::get('db');
        $this->_logger = Zend_Registry::get('batch_logger');
    }
    
    public function run() {
        $this->_init();
        $this->_logger->log('START run ' . __FILE__, Zend_Log::INFO);

        //test 1
        $row = $this->_db->fetchRow('SELECT * FROM dummy');
        print_r($row);

        //test 2
        $dummy = new Lib_Db_Table_Dummy($this->_db);
        $where = $dummy->getAdapter()->quoteInto('id = ?', 1);
        $data = array(
            'inf1' => 'Test1',
            'inf2' => 'Test2'
        );
        $dummy->update($data, $where);

        //test 3
        $dummy = new Lib_Db_Table_Dummy($this->_db);
        $row = $dummy->find(1)->toArray();
        print_r($row);

        //test 4
        $dataObj = Lib_Db_Util::getDataObject('dummy');
        $row = $dataObj->getRow(array('id' => 1));
        print_r($row);

        $this->_logger->log('END run ' . __FILE__, Zend_Log::INFO);
    }
}

$batch = new Batch_Sample();
$batch->run();
