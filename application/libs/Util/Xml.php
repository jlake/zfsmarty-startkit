<?php
/**
 * XML処理機能
 * @author ou
 *
 */
class Lib_Util_Xml
{
    /**
     * 簡単なXMLを解析する
     *
     * @param   string $xmlstr  XML文字列
     * @return  array
     */
    public static function parseSimpleXml($xmlstr)
    {
        $xmlobj = simplexml_load_string($xmlstr);
        return json_decode(json_encode($xmlobj), true);
    }

    /**
     * 名前空間使っているXMLを解析する
     *
     * @param   string $xmlstr  XML文字列
     * @return  array
     */
    public static function parseNamespaceXml($xmlstr)
    {
        $xmlstr = preg_replace('/\sxmlns="(.*?)"/', ' _xmlns="${1}"', $xmlstr);
        $xmlstr = preg_replace('/<(\/)?(\w+):(\w+)/', '<${1}${2}_${3}', $xmlstr);
        $xmlstr = preg_replace('/(\w+):(\w+)="(.*?)"/', '${1}_${2}="${3}"', $xmlstr);
        $xmlobj = simplexml_load_string($xmlstr);
        return json_decode(json_encode($xmlobj), true);
    }
}