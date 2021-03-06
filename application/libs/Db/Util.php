<?php
/**
 * 共通 Db 操作関数
 * @author ou
 */
class Lib_Db_Util
{
    /**
     * DB接続の取得
     * 
     * @param object $dbConfig DB接続設定情報
     * @return Zend_Db
     */
    public static function getZendDb($dbConfig)
    {
        if(!isset($dbConfig->adapter)) {
            return null;
        }
        try {
            $db = Zend_Db::factory($dbConfig->adapter, $dbConfig->toArray());
            $db->getConnection();
        } catch (Zend_Exception $e){
            $db = null;
        }
        return $db;
    }

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
        $words = explode('_', $tableName);
        foreach($words as &$word) {
            $word = ucfirst($word);
        }
        return 'Lib_Db_Table_' . join('', $words);
    }


    /**
     * 検索条件でWhere条件文字列の取得(jqGrid用)
     * 
     * @param $field  フィルド名
     * @param $op  比較タイプ
     * @param $value  値
     * @return  string   Where条件文字列
     */
    public static function getWhereString($field, $op, $value)
    {
        $db = Zend_Registry::get('db');
        /*
        bw - begins with ( LIKE val% )
        eq - equal ( = )
        ne - not equal ( <> )
        lt - little ( < )
        le - little or equal ( <= )
        gt - greater ( > )
        ge - greater or equal ( >= )
        ew - ends with (LIKE %val )
        cn - contain (LIKE %val% )
        */
        $whereStr = '';
        switch($op) {
            case 'eq':
                if($value === NULL) {
                    $whereStr = $field ." IS NULL";
                } else {
                    $whereStr = $field ." = " . $db->quote($value);
                }
                break;
            case 'ne':
                if($value === NULL) {
                    $whereStr = $field ." IS NOT NULL";
                } else {
                    $whereStr = $field ." <> " . $db->quote($value);
                }
                break;
            case 'lt':
                $whereStr = $field ." < " . $db->quote($value);
                break;
            case 'le':
                $whereStr = $field ." <= " . $db->quote($value);
                break;
            case 'gt':
                $whereStr = $field ." > " . $db->quote($value);
                break;
            case 'ge':
                $whereStr = $field ." >= " . $db->quote($value);
                break;
            case 'bw':
                $whereStr = $field ." LIKE " . $db->quote($value . '%');
                break;
            case 'ew':
                $whereStr = $field ." LIKE " . $db->quote('%' . $value);
                break;
            case 'cn':
                $whereStr = $field ." LIKE " . $db->quote('%' . $value. '%');
                break;
            default:
                $whereStr = $field ." = " . $db->quote($value);
                break;
        }
        return $whereStr;
    }
    
    /**
     * フィルタでWhere条件配列の取得(jqGrid用)
     * 
     * @param $filter  フィルタ
     * @param $aliasMap  テーブル別名 → カラム のマップ
     * @param $emptyAsNull  空文字列は NULL として検索
     * @return  Array   Where条件配列
     */
    public static function getWhereByFilter($filter, $aliasMap = array(), $emptyAsNull = false)
    {
        $whereArray = array();
        if(empty($filter)) {
            //フィルタ なし
            return $whereArray;
        }
        //フィルタ あり
        if(!is_array($filter)) {
            $filter = json_decode($filter, true);
        }
        //	filters: {"groupOp":"AND","rules":[{"field":"id","op":"lt","data":"1"},{"field":"inf1","op":"cn","data":"7"}]}
        foreach($filter['rules'] as $rule) {
            $field = $rule['field'];
            foreach($aliasMap as $alias => $fields) {
                if(in_array($field, $fields)) {
                    $field = $alias.'.'.$field;
                    break;
                }
                if(isset($fields[$field])) {
                    if(preg_match('/(count|sum|max|min|avg)\(/i', $fields[$field])) {
                        $field = '';
                    } else {
                        $field = $fields[$field];
                    }
                    break;
                }
            }
            if($field != '') {
                if($emptyAsNull && $rule['data'] === '') {
                    $rule['data'] = NULL;
                }
                $whereArray[] = self::getWhereString($field, $rule['op'], $rule['data']);
            }
        }
        return $whereArray;
    }
}
