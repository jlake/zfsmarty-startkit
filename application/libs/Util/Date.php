<?php
/**
 * 日付処理機能
 * @author ou
 *
 */
class Lib_Util_Date {

    /**
     * 現在の日時文字列の取得
     *
     * @param   string $format 日付の形式文字列
     * @return  string
     */
    public static function getNow($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    /**
     * DBから現在の日時文字列の取得
     *
     * @param   string $format 日付の形式文字列
     * @return  string
     */
    public static function getNowFromDb($format = '%Y-%m-%d %H:%i:%s')
    {
        $db = Zend_Registry::get('db');
        $format = $db->quote($format);
        return $db->fetchOne("SELECT date_format(CURRENT_TIMESTAMP, $format) as result");
    }
    /* PostgreSQL
    public static function getNowFromDb($format = 'yyyy-mm-dd hh24:mi:ss')
    {
        $db = Zend_Registry::get('db');
        $format = $db->quote($format);
        return $db->fetchOne("SELECT to_char(CURRENT_TIMESTAMP, $format) as result");
    }
    */

    /**
     * ローカル時間からUTC時間に変換
     *
     * @param   string $dateStr 日付の文字列
     * @param   string $format 日付の形式文字列
     * @return  string
     */
    public static function local2utc($dateStr, $format = 'Y-m-d H:i:s')
    {
        $tz = date_default_timezone_get();
        $time = strtotime($dateStr);
        date_default_timezone_set('UTC');
        $utcDate = date($format, $time);
        date_default_timezone_set($tz);
        return $utcDate;
    }

    /**
     * UTC時間からローカル時間に変換
     *
     * @param   string $dateStr 日付の文字列
     * @param   string $format 日付の形式文字列
     * @return  string
     */
    public static function utc2local($dateStr, $format = 'Y-m-d H:i:s')
    {
        $time = strtotime($dateStr. ' UTC');
        return date($format, $time);
    }
}