<?php
/**
 * 共通 Db操作データオブジェクト
 * @author ou
 */
class Lib_Db_DataObject
{
    protected $_db;
    protected $_table;
    protected $_tableName;
    protected $_tablePrimary;

    protected $_autoLog = false;
    protected $_logQueue = array();
    protected $_logModel = null;

    protected $_defaults = array(
        'table' => null,
        'db' => 'db',
        'auto_log' => false,
        'log_model' => null
    );

    /**
     * コンストラクタ
     *
     * @param   array $params  パラメータの配列
     * @return  void
     */
    public function __construct($params = array()) 
    {
        $params = array_merge($this->_defaults, $params);
        if(isset($params['db'])) {
            // データベース指定（Object または string）
            $this->setDb($params['db']);
        }
        if(isset($params['table'])) {
            // テーブル指定
            $this->setTable($params['table']);
        }
        if(isset($params['auto_log'])) {
            // 自動ログ記入/記入しない設定
            $this->_autoLog = $params['auto_log'];
            if($this->_autoLog) {
                $this->_logQueue = array();
            }
        }
        if(isset($params['log_model'])) {
            // トランザクションの場合、ログ記入のデータオブジェクトを指定
            $this->_logModel = $params['log_model'];
        }
    }

    /**
     * DBオブジェクトのセット
     *
     * @param   object $db  DBオブジェクト
     * @return  object
     */
    public function setDb($db)
    {
        if(is_string($db)) {
            $db = Zend_Registry::get($db);
        }
        $this->_db = $db;
        Zend_Db_Table::setDefaultAdapter($db);
        return $this;
    }

    /**
     * DBオブジェクトの取得
     *
     * @return  Zend_Db_Adapter_Abstract
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * DBテーブルオブジェクトのセット
     *
     * @param   Zend_Db_Table_Abstract/string $table  テーブルモデル（）
     * @return  object
     */
    public function setTable($table)
    {
        if(is_string($table)) {
            //$table = new Zend_Db_Table($table);
            $dbTableClass = self::getTableModelClass($table);
            $table = new $dbTableClass($this->_db);
        }
        if(!($table instanceof Zend_Db_Table_Abstract)) {
            throw new Exception('Table オブジェクトは Zend_Db_Table ではありません');
        }
        $this->_table = $table;
        $info = $table->info();
        $this->_tableName = $info['name'];
        $this->_tablePrimary = $info['primary'];
        if(!isset($this->_db)) {
            $this->setDb($table->getAdapter());
        }
        return $this;
    }

    /**
     * DBテーブルオブジェクトの取得
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * DBテーブル名の取得
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * プライマリキーの取得
     *
     * @return array
     */
    public function getTablePrimary()
    {
        return $this->_tablePrimary;
    }

    /**
     * すべてのレコード取得
     *
     * @param   array $cond  条件配列
     * @param   array/string $fields  フィールド名（配列可）
     * @return  array
     */
    public function getRows($cond = array(), $fields="*", $order=NULL, $limit=NULL, $start=0)
    {
        return $this->select($cond, $fields, $order, $limit, $start);
    }

    /**
     * 一番先頭のレコード取得
     *
     * @param   array $cond  条件配列
     * @param   array/string $fields  フィールド名（配列可）
     * @return  array
     */
    public function getRow($cond = array(), $fields="*")
    {
        if(is_string($fields) && $fields != '*') {
            $fields = preg_split('/\s*,\s*/', $fields);
        }
        $select = $this->_db->select()->from(
            $this->getTableName(),
            $fields
        );
        if(!empty($cond)) {
            $select = self::appendCondToSelect($select, $cond);
        }
        $row = $this->_db->fetchRow($select);
        return $row ? $row : array();
    }

    /**
     * 指定フィールドの値を取得
     *
     * @param   array $cond  条件配列
     * @param   array/string $field  フィールド名
     * @param   array/string $order  表示順（Order By）
     * @return  array
     */
    public function getOne($cond = array(), $field, $order = NULL)
    {
        $recs = $this->select($cond, $field, $order, 1, 0);
        return isset($recs) && isset($recs[0][$field]) ? $recs[0][$field] : '';
    }

    /**
     * レコード数の取得（共通）
     *
     * @param   array $cond  条件配列
     * @param   array $tableName  テーブル名(省略可)
     * @return  array
     */
    public function getCount($cond = array(), $tableName = NULL)
    {
        if(!isset($tableName)) $tableName = $this->getTableName();
        $select = $this->_db->select()->from($tableName, 'count(*)');
        if(!empty($cond)) {
            $select = self::appendCondToSelect($select, $cond);
        }
        return $this->_db->fetchOne($select);
    }

    /**
     * すべてのレコード取得
     *
     * @param   string $field1  １番目のフィールド名（key）
     * @param   string $field2  ２番目のフィールド名（value）
     * @param   array $cond  条件配列
     * @return  array
     */
    public function getPairs($field1, $field2, $cond = array())
    {
        $select = $this->_db->select()->from(
            $this->getTableName(),
            array($field1, $field2)
        );
        if(!empty($cond)) {
            $select = self::appendCondToSelect($select, $cond);
        }
        return $this->_db->fetchPairs($select);
    }

    /**
     * 指定カラムの値（配列）の取得
     *
     * @param   string $field  フィールド名（key）
     * @param   array $cond  条件配列
     * @return  array
     */
    public function getCol($field, $cond = array())
    {
        $select = $this->_db->select()->from(
            $this->getTableName(),
            $field
        );
        if(!empty($cond)) {
            $select = self::appendCondToSelect($select, $cond);
        }
        return $this->_db->fetchCol($select);
    }

    /**
     * レコード配列の取得（共通）
     *
     * @param   array $cond  条件配列
     * @param   array/string $fields  条件配列
     * @param   array/string $order  表示順（Order By）
     * @param   number $limit  取得件数
     * @param   number $start  取得件数のスタート
     * @return  array
     */
    public function select(array $cond, $fields="*", $order=NULL, $limit=NULL, $start=0)
    {
        if(is_string($fields) && $fields != '*') {
            $fields = preg_split('/\s*,\s*/', $fields);
        }
        $select = $this->_db->select()->from($this->getTableName(), $fields);
        $select = self::appendCondToSelect($select, $cond);
        if(isset($order)) {
            $select->order($order);
        }
        if(isset($limit)) {
            $select->limit($limit, $start);
        }
        return $this->_db->fetchAll($select);
    }

    /**
     * データ追加（共通）
     *
     * @param   array $data  更新データ配列
     * @return  number
     */
    public function insert($data)
    {
        $data = $this->_addExtraInfo($data);
        $data['create_date'] = new Zend_Db_Expr('CURRENT_TIMESTAMP');
        if($this->_autoLog) {
            $log = $this->_getLogText('INSERT', $data);
            $this->appendLog($log);
        }
        return $this->_table->insert($data) ? 1 : 0;
    }


    /**
     * データ更新（共通）
     *
     * @param   array $data  更新データ配列
     * @param   array $cond  条件配列
     * @return  number
     */
    public function update(array $data, $cond = array())
    {
        $data = $this->_addExtraInfo($data);
        if($this->_autoLog) {
            $log = $this->_getLogText('UPDATE', $data, $cond);
            $this->appendLog($log);
        }
        return $this->_table->update($data, self::getWhereArray($cond));
    }

    /**
     * データ削除（共通）
     *
     * @param   array $cond  条件配列
     * @return  number
     */
    public function delete($cond = array())
    {
        if($this->_autoLog) {
            $log = $this->_getLogText('DELETE', NULL, $cond);
            $this->appendLog($log);
        }
        return $this->_table->delete(self::getWhereArray($cond));
    }

    /**
     * データ追加・更新（共通）
     *
     * @param   array $data  更新データ配列
     * @return  number
     */
    public function save(array $data)
    {
        $cond = array();
        foreach($this->getTablePrimary() as $key) {
            if(!array_key_exists($key, $data)) {
                $cond = array();
                break;
            }
            $cond[$key] = $data[$key];
        }
        if(!empty($cond) && $this->getCount($cond) > 0) {
            return $this->update($data, $cond);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * 直接 SQL を実行
     *
     * @param   string $sql  SQL文字列
     * @return  number
     */
    public function query($sql)
    {
        return $this->_db->query($sql);
    }

    /**
     * データ配列からZendのWhere配列に変換
     *
     * @param   array $cond  条件配列 (フィールド名 => バリュー)
     * @param   boolean $notFlg  NOT フラグ
     * @return  number
     */
    public static function getWhereArray($cond, $notFlg=false)
    {
        $db = Zend_Registry::get('db');
        $where = array();
        foreach($cond as $name => $value) {
            if(strToUpper($name) == '_NOT_' && is_array($value)) {
                $where = array_merge($where, self::getWhereArray($value, true));
                continue;
            }
            if(is_array($value)){
                $where[] = $notFlg ?
                        $db->quoteInto(" $name NOT IN (?) ", $value)
                        : $db->quoteInto(" $name IN (?) ", $value);
            } elseif($value === NULL){
                $where[] = $notFlg ?
                        "$name IS NOT NULL"
                        : "$name IS NULL";
            } else {
                $where[] = $notFlg ?
                        $db->quoteInto("$name!=?", $value)
                        : $db->quoteInto("$name=?", $value);
            }
        }
        return $where;
    }

    /**
     * Zend_Db_Select オブジェクトに条件追加
     *
     * @param   object $select  Zend_Db_Select オブジェクト
     * @param   array $cond  条件配列
     * @return  number
     */
    public static function appendCondToSelect($select, $cond)
    {
        $wheres = self::getWhereArray($cond);
        
        foreach($wheres as $where) {
            $select->where($where);
        }
        return $select;
    }

    /**
     * 次のシーケンスIDを取得
     *
     * @param   string $seqName  テーブル名
     * @return  number
     */
    public function nextSeqNo($seqName)
    {
        return $this->_db->fetchOne("SELECT NEXTVAL(".$this->_db->quote($seqName).")");
    }

    /**
     * Max+1で新しいIDを取得
     *
     * @param   string $fieldName  フィールド名
     * @param   array $cond  条件配列（省略可）
     * @return  number
     */
    public function maxPlus1($fieldName, $cond=array())
    {
        $select = $this->_db->select()->from(
            $this->getTableName(),
            "MAX(".$fieldName.")"
        );
        $select = self::appendCondToSelect($select, $cond);
        $value = $this->_db->fetchOne($select);
        if($value == '') {
            return 1;
        }
        return $value + 1;
    }

    /**
     * ログ追加
     *
     * @param   string $log  ログ文字列
     * @return  string
     */
    public function appendLog($log)
    {
        if(isset($this->_logModel)) {
            $this->_logModel->appendLog($log);
        } else {
            $this->_logQueue[] = $log;
        }
    }

    /**
     * DB操作ログの取得
     *
     * @param   string $actionTag  アクションタグ(INSERT, UPDATE, DELETE ...)
     * @param   array $data  データ
     * @param   array $cond  条件
     * @return  string
     */
    protected function _getLogText($actionTag, $data, $cond)
    {
        $userId = self::getUserId();
        $logText = "-- DB操作 --\n";
        $logText .= "対象テーブル: ".$this->getTableName()." \n";
        $logText .= "ユーザID: $userId \n";
        $logText .= "操作: $actionFlg \n";

        if($data) {
            $logText .= 'データ: '.print_r($data, true)."\n";
        }
        if($cond) {
            $logText .= '条件: '.print_r($cond, true)."\n";
        }
        return $logText;
    }

    /**
     * トランザクションを開始
     *
     * @param   なし
     * @return  void
     */
    public function beginTransaction()
    {
        $this->_db->beginTransaction();
        $this->_logQueue = array();
    }

    /**
     * ロールバック
     *
     * @param   なし
     * @return  void
     */
    public function rollBack()
    {
        $this->_db->rollBack();
        $this->_logQueue = array();
    }

    /**
     * コミット
     *
     * @param   なし
     * @return  void
     */
    public function commit()
    {
        $this->_db->commit();
        $this->_writeLog();
    }

    /**
     * アクションログを書き出す
     *
     * @param   なし
     * @return  string
     */
    protected function _writeLog()
    {
        if(isset($this->_logModel)) {
            return;
        }
        $module = Zend_Registry::get('module');
        if(!$module) $module = 'site';
        $logger = Zend_Registry::get($module . '_logger');
        foreach($this->_logQueue as $i=>$log) {
            $logger->log($log, Zend_Log::INFO);
        }
        $this->_logQueue = array();
    }


    /**
     * データに更新者と更新日付などの情報を自動追加
     *
     * @param   array $data  元データ配列
     * @return  array
     */
    public function _addExtraInfo($data)
    {
        if(!isset($data['set_nm'])) {
            $data['set_nm'] = self::getUserId();
        }
        if(!isset($data['set_date'])) {
            $data['set_date'] = new Zend_Db_Expr('CURRENT_TIMESTAMP');
        }
        return $data;
    }

    /**
     * ユーザIDの取得
     *
     * @param   なし
     * @return  string
     */
    public static function getUserId()
    {
        try {
            $module = Zend_Registry::get('module');
        } catch(Exception $e) {
            $module = 'site';
        }
        if($module == 'batch') {
            return 'system';
        }
        $namespace = ucwords($module) . '_Auth';
        $authSession = new Zend_Session_Namespace($namespace);
        if($module == 'admin') {
            return $authSession->userInfo['admin_user_id'];
        } elseif($module == 'site') {
            return $authSession->userInfo['user_id'];
        }
        return 'system';
    }
}