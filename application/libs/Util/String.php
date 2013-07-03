<?php
/**
 * 文字列処理機能
 * @author ou
 *
 */
class Lib_Util_String
{

    /**
     * 文字列トランケート関数
     *
     * @param   string $string  元の文字列
     * @param   int $length  長さ
     * @param   string $etc  省略を示すの文字列
     * @return  string
     */
    public static function truncate($string, $length = 80, $etc = '...')
    {
        if (strlen($string) > $length) {
            return substr($string, 0, $length - strlen($etc)).$etc;
        }
        return $string;
    }

    /**
     * マルチバイトに対応したトランケート関数
     *
     * @param   string $string  元の文字列
     * @param   int $length  元の文字列
     * @param   string $etc  省略を示すの文字列
     * @return  string
     */
    public static function mbTruncate($string, $length = 80, $etc = '...')
    {
        if (mb_strlen($string) > $length) {
            return mb_substr($string, 0, $length - mb_strlen($etc)).$etc;
        }
        return $string;
    }

    /**
     * マルチバイトに対応した指定した幅で文字列を丸める関数
     *
     * @param   string $string  元の文字列
     * @param   int $length  元の文字列
     * @param   string $etc  省略を示すの文字列
     * @param   string $encoding  文字コード
     * @return  string
     */
    public static function mbFixWidth($string, $length = 80, $etc = '...', $encoding = 'UTF-8')
    {
        if (mb_strlen($string) > $length) {
            return mb_strimwidth($string, 0, $length - mb_strlen($etc), $etc, $encoding);
        }
        return $string;
    }

    /**
     * マルチバイトに対応した指定した幅で文字列を丸める関数(バイト数)
     *
     * @param   string $string  元の文字列
     * @param   int $length  元の文字列
     * @param   string $etc  省略を示すの文字列
     * @param   string $encoding  文字コード
     * @return  string
     */
    public static function mbFixWidthB($string, $length = 80, $etc = '...', $encoding = 'UTF-8')
    {
        if (strlen($string) > $length) {
            return mb_strcut($string, 0, $length - strlen($etc), $encoding).$etc;
        }
        return $string;
    }
}