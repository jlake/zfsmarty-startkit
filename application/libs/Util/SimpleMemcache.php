<?php
/**
 * シンプルメモキャッシュ機能
 *
 * @author ou
 */
class Lib_Util_SimpleMemcache
{
    const CACHE_LIFETIME = 3600; // キャシュ有効期間 単位:秒

    private $_memcache = NULL; // キャシュオブジェクト
    private $_connected = false; // 接続済フラグ

    /**
     * 初期化
     */
    public function __construct($cacheServers = array(array("host"=>"127.0.0.1", "port"=> 11211, "weight"=>1)))
    {
        $this->_memcache = new Memcache(); 
        foreach($cacheServers as $server) {
            if($this->_memcache->addServer($server['host'], $server['port'], $server['weight'])) {
                $this->_connected = true;
            }
        }
        return $this->_connected;
    }

    /** 
     * キャッシュデータの取得
     */ 
    function get($key) { 
        if (!$this->_connected) { 
            return NULL; 
        } 
        return $this->_memcache->get($key); 
    } 

    /** 
     * キャッシュデータのセット
     */ 
    function set($key, $value, $expires = self::CACHE_LIFETIME) { 
        if (!$this->_connected) { 
            return NULL; 
        } 
        if ($expires < 1) { 
            $expires = 1;
        }
        return $this->_memcache->set($key, $value, 0, time()+$expires); 
    } 

    /** 
     * キャッシュデータの削除
     */ 
    function delete($key) { 
        if (!$this->_connected) { 
            return NULL; 
        } 
        return $this->_memcache->delete($key); 
    }
}
