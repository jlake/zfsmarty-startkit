<?php
/**
 * 日付処理機能
 * @author ou
 *
 */
class Lib_Util_Date
{

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

    /**
     * 指定書式でフォーマットした日付の取得
     *
     * @param   string $dateStr 日時文字列
     * @param   string $format 日付の形式文字列
     * @return  string
     */
    public static function format($dateStr, $format = 'Y-m-d H:i:s')
    {
        $date = new DateTime($dateStr);
        return $date->format($format);
    }

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

    /**
     * ダウンロード期限日の取得
     *
     * @param   string $dlLimitDays 日付の文字列
     * @param   boolean $asArray trueの場合、結果を配列で返す
     * @return  string
     */
    public static function getDlLimitDate($dlLimitDays = 0, $asArray = false)
    {
        $date = new Zend_Date();
        if($dlLimitDays > 0) {
            $date->add($dlLimitDays, Zend_Date::DAY);
        } else {
            $date->add(1, Zend_Date::YEAR)->sub(1, Zend_Date::DAY);
        }
        if($asArray) {
            return $date->toArray();
        }
        return $date->toString();
    }
}