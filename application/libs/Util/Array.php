<?php
/**
 * 配列処理機能
 * @author ou
 *
 */
class Lib_Util_Array
{

    /**
     * 空文字列をNULLに変換
     *
     * @param   array/string $var  変数名
     * @return  array/string
     */
    public static function emptyToNull($var)
    {
        if(is_array($var)) {
            foreach($var as $k=>$val) {
                $var[$k] = ($val === '') ? NULL : $val;
            }
        } else {
            $var = ($var === '') ? NULL : $var;
        }
        return $var;
    }

    /**
     * 配列から必要な項目を抽出
     *
     * @param array $data  元データ配列
     * @param array $keys  取得したい項目のキーの配列
     * @return  array
     */
    public static function getPart($data, $keys)
    {
        $result = array();
        foreach($keys as $k) {
            if(isset($data[$k])) {
                $result[$k] = $data[$k];
            }
        }
        return $result;
    }

    /**
     * 列を指定で配列のソート
     * @param $args  配列, 列, SORT_ASC/SORT_DESC ...
     * @return array
     */
    public static function multiSort()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row) {
                    $tmp[$key] = $row[$field];
                }
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    /**
     * 配列から XML へ変換
     *
     * @param array $data             配列形式のデータ
     * @param array $encoding         文字コード
     * @param string $rootNodeName    ルートノード名称 （デフォールトは data)
     * @param SimpleXMLElement $xml   XML オブジェクト（再帰用）
     * @return string XML
     */
    public static function toXml($data, $encoding = 'UTF-8', $rootNodeName = 'data', $xml = null)
    {
        if ($xml == null) {
            $xml = simplexml_load_string('<?xml version="1.0" encoding="'.$encoding.'"?><'.$rootNodeName.' />');
        }
        
        foreach($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item_' . (string) $key;
            }
            
            $key = preg_replace('/\W+/i', '', $key);
            
            if (is_array($value)) {
                $node = $xml->addChild($key);
                self::toXml($value, $encoding, $rootNodeName, $node);
            } else {
                //$value = htmlentities($value);
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}