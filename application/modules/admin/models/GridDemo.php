<?php
/**
 * Site module
 * Dummy モデル
 * @author ou
 */
class Admin_Model_GridDemo
{
    protected $_db;

    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct()
    {
        $this->_db = Zend_Registry::get('db');
    }

    /**
     * Dummyデータの取得(jqGridで表示用)
     *
     * @param   array $params  引数の配列
     * @return  array
     */
    public function getDemo1Data($params)
    {
        $dataSelect = $this->_db->select()->from(
                'dummy',
                array('id', 'inf1', 'inf2')
            );
        $countSelect = $this->_db->select()->from(
                'dummy',
                'COUNT(1)'
            );
        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['rows']) ? $params['rows'] : 20;
        $dataSelect->limit(
            $limit,
            ($page - 1) * $limit
        );
        if (isset($params['sidx'])) {
            $sortOrder = $params['sidx'];
            if (isset($params['sord'])) {
                $sortOrder .= ' '.$params['sord'];
            }
            $dataSelect->order($sortOrder);
        }
        Lib_Util_Log::firebug($dataSelect->__toString());
        $rows = array();
        foreach($this->_db->fetchAll($dataSelect) as $row) {
            $rows[] = array(
                'id' => $row['id'],
                'cell' => array_values($row)
            );
        }
        $count = $this->_db->fetchOne($countSelect);
        return array(
            'page' => $page,
            'total' => ceil($count/$limit),
            'records' => $count,
            'rows' => $rows
        );
    }



    /**
     * Dummyデータの取得(jqGridで表示用)
     *
     * @param   array $params  引数の配列
     * @return  array
     */
    public function getDemo2Data($params)
    {
        $aliasMap = array(
            'd' => array(
                'id',
                'inf1',
                'inf2',
            ),
        );

        $dataSelect = $this->_db->select()->from(
                array('d' => 'dummy'),
                $aliasMap['d']
            );
        $countSelect = $this->_db->select()->from(
                array('d' => 'dummy'),
                'COUNT(1)'
            );

        if(!empty($params['_search'])) {
            if(!empty($params['searchField'])) {
                $where =  self::getWhereString($params['searchField'], $params['searchOper'], $params['searchString']);
                $dataSelect->where($where);
                $countSelect->where($where);
            }
            //フィルタ
            if(!empty($params['filter'])) {
                $whereArray = self::getWhereByFilter($params['filter'], $aliasMap);
                if($params['filter']['groupOp'] == 'AND') {
                    foreach($whereArray as $where) {
                        $dataSelect->where($where);
                        $countSelect->where($where);
                    }
                } else {
                    foreach($whereArray as $where) {
                        $dataSelect->orWhere($where);
                        $countSelect->orWhere($where);
                    }
                }
            }
        }

        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['rows']) ? $params['rows'] : 20;
        $dataSelect->limit(
            $limit,
            ($page - 1) * $limit
        );
        if (isset($params['sidx'])) {
            $sortOrder = $params['sidx'];
            if (isset($params['sord'])) {
                $sortOrder .= ' '.$params['sord'];
            }
            $dataSelect->order($sortOrder);
        }
        //Lib_Util_Log::firebug($dataSelect->__toString());
        $rows = array();
        foreach($this->_db->fetchAll($dataSelect) as $row) {
            $rows[] = array(
                'id' => $row['id'],
                'cell' => array_values($row)
            );
        }
        $count = $this->_db->fetchOne($countSelect);
        return array(
            'page' => $page,
            'total' => ceil($count/$limit),
            'records' => $count,
            'rows' => $rows
        );
    }

    /**
     * 検索条件でWhere条件文字列の取得
     * 
     * @param $field  フィルド名
     * @param $op  比較タイプ
     * @param $value  値
     * @return  string   Where条件文字列
     */
    public static function getWhereString($field, $op, $value)
    {
        Lib_Util_Log::firebug($field.$op.$value);
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
                $whereStr = $field ." = " . $db->quote($value);
                break;
            case 'ne':
                $whereStr = $field ." <> " . $db->quote($value);
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
     * フィルタでWhere条件配列の取得
     * 
     * @param $filter  フィルタ
     * @param $aliasMap  テーブル別名 → カラム のマップ
     * @return  Array   Where条件配列
     */
    public static function getWhereByFilter($filter, $aliasMap = array())
    {
        if(empty($filter)) {
            //フィルタ なし
            return $whereArray;
        }
        $whereArray = array();
        //フィルタ あり
        //	filter: {"groupOp":"AND","rules":[{"field":"a.total","op":"lt","data":"1000"},{"field":"a.tax","op":"lt","data":"100"}],"groups":[]}
        foreach($filter['rules'] as $rule) {
            $field = preg_replace('/^\w\./', '', $rule[' field']);
            foreach($aliasMap as $alias => $fields) {
                if(in_array($field, $fields)) {
                    $field = $alias.'.'.$field;
                    break;
                }
            }
            $whereArray[] = self::getWhereString($field, $rule['op'], $rule['data']);
        }
        return $whereArray;
    }
}