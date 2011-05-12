<?php
/**
 * 共通 Zend_Db_Table の拡張
 * @author ou
 */
class Lib_Db_Table extends Zend_Db_Table_Abstract
{
    public function __construct($db = null)
    {
        if(isset($db)) {
            $this->_setAdapter($db);
        } else {
            $this->_setAdapter(Zend_Registry::get('db'));
        }
    }
}