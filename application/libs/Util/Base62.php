<?php
/**
 * Base62エンコード・デコード機能
 * @author ou
 *
 */
class Lib_Util_Base62
{
    protected static $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * エンコード
     *
     * @param   integer $val 数字 (最大は 2^31-1 = 2147483647)
     * @return  string
     */
    public static function encode($val) {
         $str = '';
         do {
             $i = $val % 62;
             $str = self::$chars[$i] . $str;
             $val = ($val - $i) / 62;
         } while($val > 0);
         return $str;
    }

    /**
     * エンコード
     *
     * @param   string $str 文字列
     * @return  integer
     */
    public static function decode($str) {
         $len = strlen($str);
         $val = 0;
         $arr = array_flip(str_split(self::$chars));
         for($i = 0; $i < $len; ++$i) {
             $val += $arr[$str[$i]] * pow(62, $len-$i-1);
         }
         return $val;
    }
}