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
    public function getDummyData($params)
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

        if(isset($params['_search']) && $params['_search'] == 'true') {
            //フィルタ
            if(!empty($params['filters'])) {
                $filters = json_decode($params['filters'], true);
                $whereArray = Lib_Db_Util::getWhereByFilter($filters, $aliasMap);
                if($filters['groupOp'] == 'OR') {
                    $where = implode(' OR ', $whereArray);
                    $dataSelect->where($where);
                    $countSelect->where($where);
                } else {
                    foreach($whereArray as $where) {
                        $dataSelect->where($where);
                        $countSelect->where($where);
                    }
                }
            } else if(!empty($params['searchField'])) {
                $where =  Lib_Db_Util::getWhereString($params['searchField'], $params['searchOper'], $params['searchString']);
                $dataSelect->where($where);
                $countSelect->where($where);
            }
        }

        $page = isset($params['page']) ? $params['page'] : 1;
        $limit = isset($params['rows']) ? $params['rows'] : 20;
        $dataSelect->limit(
            $limit,
            ($page - 1) * $limit
        );
        if (!empty($params['sidx'])) {
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
}