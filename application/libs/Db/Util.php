<?php
/**
 * 共通 Db 操作関数
 * @author ou
 */
class Lib_Db_Util
{
    /**
     * テーブル名から DataObject の作成
     *
     * @param string $tableName  テーブル名
     * @param object $db テーブル所在DBアダプタ
     *
     * @return string  DbTableモデルクラス名
     */
    public static function getDataObject($tableName, $db = null)
    {
        if(!isset($dbAdapter)) {
            $db = Zend_Registry::get('db');
        }
        $dbTableClass = self::getTableModelClass($tableName);
        return new Lib_Db_DataObject(array(
            'table' => new $dbTableClass($db)
        ));
    }

    
    /**
     * テーブル名から dbTable モデルクラスの取得
     *
     * @param string $tableName テーブル名
     *
     * @return number 反映件数
     */
    public static function getTableModelClass($tableName)
    {
        $words = split('_', $tableName);
        foreach($words as &$word) {
            $word = ucfirst($word);
        }
        return 'Lib_Db_Table_' . join('', $words);
    }
}