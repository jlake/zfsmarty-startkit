<?php
/**
 * Soap 通信テスト用サーバ
 * @author ou
 */
class Lib_Soap_TestServer
{
    /**
     * Class constructor
     *
     */
    public function __construct() {
    }

    /**
     * Dummy テーブルの内容を返す
     *
     * @return array
     */
    public function getDummyAll()
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(
            'dummy',
            array(
                'id',
                'inf1',
                'inf2'
            )
        );
        return $db->fetchAll($select);
    }

    /**
     * Dummy テーブルの一行を返す(id指定)
     *
     * @return array
     */
    public function getDummyRow($id)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(
            'dummy',
            array(
                'id',
                'inf1',
                'inf2'
            )
        )->where(
            'id = ?', $id
        );
        return $db->fetchRow($select);
    }

}