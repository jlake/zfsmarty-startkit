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
     * 指定日付の当月の日数（最終日）の取得
     *
     * @param   string $dateStr 日付の文字列
     * @return  string
     */
    public static function getMonthDays($dateStr)
    {
        return date('t', strtotime($dateStr));
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
     * 日付を比較する
     *
     * @param   string $dateStr1 日付1(文字列)
     * @param   string $dateStr2 日付2(文字列)
     * @return  int
     */
    public static function compare($dateStr1, $dateStr2)
    {
        $time1 = strtotime($dateStr1);
        $time2 = strtotime($dateStr2);
        if ($time1 > $time1) {
             return 1;
        } else if ($time1 < $time1) {
             return -1;
        }
        return 0;
    }

    /**
     * 日時文字列をチェクする
     *
     * @param   string $dateTime 日時(文字列)
     * @return  int
     */
    public static function isValidDateTime($dateTime)
    {
        if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
            if (checkdate($matches[2], $matches[3], $matches[1])) {
                return true;
            }
        }
        return false;
    }
}
