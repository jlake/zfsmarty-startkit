<?php
/**
 * Site module
 * Dummy モデル
 * @author ou
 */
class Site_Model_Dummy
{
    private $_dataObj;

    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct()
    {
        $this->_dataObj = new Lib_Db_DataObject(array(
            'table' => new Lib_Db_Table_Dummy
        ));
        //$this->_dataObj = Lib_Db_Util::getDataObject('dummy');
    }

    /**
     * レコードデータの取得
     *
     * @param   $params  引数の配列
     * @return  array
     */
    public function getRowData($params)
    {
        $cond = array(
            'id' => $params['id']
        );
        return $this->_dataObj->getRow($cond);
    }

    /**
     * レコードデータの取得(Zend_Db_Selectのサンプル)
     *
     * @param   $params  引数の配列
     * @return  array
     */
    public function getRowDataByZendSelect($params)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(
                'dummy',
                array('id', 'inf1', 'inf2')
            )->where(
                'id=?', $params['id']
            );
        return $db->fetchRow($select);
    }

    /**
     * レコードデータ取得(SQLのサンプル)
     *
     * @param   $params  引数の配列
     * @return  array
     */
    public function getRowDataBySql($params)
    {
        $db = Zend_Registry::get('db');
        $sql = "
            SELECT
                id,
                inf1,
                inf2
            FROM
                dummy
            WHERE
                id=:id
        ";
        $stmt = $db->query($sql, array('id' => $params['id']));
        $rows = $stmt->fetchAll();
        return $rows[0];
    }

    /**
     * データの保存（UPDATE または INSERT）
     *
     * @param   $params  引数の配列
     * @return  array
     */
    public function saveData($params)
    {
        if(empty($params['id'])) {
            // シーケンスから新しいIDの取得
            //pgsql
            //$params['id'] = $this->_dataObj->nextSeqNo('dummy_id_seq');
            $params['id'] = NULL;
        }
        return $this->_dataObj->save(array(
            'id' => $params['id'],
            'inf1' => $params['inf1'],
            'inf2' => $params['inf2'],
        ));
    }

    /**
     * データの保存（Zend_Db関数のサンプル）
     *
     * @param   $params  引数の配列
     * @return  mixed
     */
    public function saveDataByZendDb($params)
    {
        $db = Zend_Registry::get('db');
        if(empty($params['id'])) {
            //pgsql
            //$newId = $db->fetchOne("SELECT NEXTVAL('dummy_id_seq')");
            //mysql
            $newId = NULL;
            $data = array(
                'id' => $newId,
                'inf1' => $params['inf1'],
                'inf2' => $params['inf2'],
            );
            return $db->insert('dummy', $data);
        } else {
            $set = array(
                'inf1' => $params['inf1'],
                'inf2' => $params['inf2'],
            );
            $where = array(
                $db->quoteInto('id = ?', $params['id']),
            );
            return $db->update('dummy', $set, $where);
        }
    }

    /**
     * データの保存（SQLのサンプル）
     *
     * @param   $params  引数の配列
     * @return  mixed
     */
    public function saveDataBySQL($params)
    {
        $db = Zend_Registry::get('db');
        if(empty($params['id'])) {
            $newId = $db->fetchOne("SELECT NEXTVAL('dummy_id_seq')");
            $inf1 = $db->quote($params['inf1']);
            $inf2 = $db->quote($params['inf2']);
            $sql = "
                INSERT INTO
                    dummy(id, inf1, inf2)
                VALUES
                    ($newId, $inf1, $inf2)
            ";
            return $db->query($sql);
        } else {
            $sql = "
                UPDATE
                    dummy
                SET
                    inf1 = :inf1,
                    inf2 = :inf2
                WHERE
                    id = :id
            ";
            $data = array(
                'id' => $params['id'],
                'inf1' => $params['inf1'],
                'inf2' => $params['inf2'],
            );
            $stmt = $db->query($sql, $data);
            return $stmt->execute();
        }
    }

    /**
     * データの削除
     *
     * @param   $params  引数の配列
     * @return  mixed
     */
    public function deleteData($params)
    {
        $cond = array(
            'id' => $params['id']
        );
        return $this->_dataObj->delete($cond);
    }

    /**
     * データの削除（Zend_Db関数のサンプル）
     *
     * @param   $params  引数の配列
     * @return  mixed
     */
    public function deleteDataByZendDb($params)
    {
        $db = Zend_Registry::get('db');
        $where = array(
            $db->quoteInto('id = ?', $params['id']),
        );
        return $db->delete('dummy', $where);
    }

    /**
     * データの削除（SQLのサンプル）
     *
     * @param   $params  引数の配列
     * @return  mixed
     */
    public function deleteDataBySql($params)
    {
        $db = Zend_Registry::get('db');
        $sql = "
            DELETE FROM
                dummy
            WHERE
                id = :id
        ";
        $data = array(
            'id' => $params['id'],
        );
        $stmt = $db->query($sql, $data);
        return $stmt->execute();
    }

    /**
     * トランザクションのテスト
     *
     * @param   $params  引数の配列
     * @return  void
     */
    public function testTransaction($params)
    {
        try {
            $this->_dataObj->beginTransaction();
            // 1 ~ 100 行のデータを挿入・更新
            for($i=1; $i<101; $i++) {
                $cnt = $this->_dataObj->getCount(array(
                    'id' => $i
                ));
                if($cnt > 0) {
                    $id = $i;
                } else {
                	//pgsql
                    //$id = $this->_dataObj->nextSeqNo('dummy_id_seq');
                    //mysql
                    $id = NULL;
                }
                $this->_dataObj->save(array(
                    'id' => $id,
                    'inf1' => $i . '行1列',
                    'inf2' => $i . '行2列',
                ));
            }
            $this->_dataObj->commit();
        } catch(Exception $ex) {
            $this->_dataObj->rollback();
        }
    }

}