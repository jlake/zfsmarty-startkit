<?php
/**
 * メッセージ処理機能
 * @author ou
 *
 */
class Lib_Util_Message {
    /**
     * 
     * ini ファイルからメッセージの取得
     * @param     string  $msgType     メッセージタイプ
     * @param     string  $msgCode     メッセージコード
     * @param     array  $replaceList  置換文字列の配列
     * @return    string
    */
    public static function get($msgType, $msgCode, $replaceList = array())
    {
        if(!$msgType || !$msgCode) {
            return '';
        }
        $msgText = '';
        $msgConfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/lang/jp/message.ini', $msgType);
        if($msgConfig) {
            $msgText = isset($msgConfig->$msgCode) ? $msgConfig->$msgCode : '';
        }
        if(!empty($replaceList)) {
            $msgText = self::replace($msgText, $replaceList);
        }
        return $msgText;
    }

    /**
     * 
     * メッセージの文字列置換処理
     * @param     string  $msgText     メッセージテキスト
     * @param     array  $replaceList  置換文字列の配列
     * @return    string
    */
    public static function replace($msgText, $replaceList = array())
    {
        if(!$msgText) {
            return '';
        }
        foreach($replaceList as $key => $value) {
            $msgText = str_replace('%'.$key. '%', $value, $msgText);
        }
        return $msgText;
    }
}