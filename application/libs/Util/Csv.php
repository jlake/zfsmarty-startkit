<?php
/**
 * CSVデータ処理機能
 * @author ou
 *
 */
class Lib_Util_Csv
{
    /**
     * 
     * CSVﾀﾞｳﾝﾛｰﾄﾞのHeaderを吐き出す
     * @param     string  $fileName     ファイル名
     * @param     string  $disposition  inline/attachment
     * @return    boolean
    */
    public static function sendCsvHeader($fileName, $disposition = 'inline')
    {
        if(headers_sent()) {
            return false;
        }

        set_time_limit(600);

        header( "Cache-Control: public" );
        header( "Pragma: public" );
        header( "Content-type: text/csv" ) ;
        header( "Content-Disposition: $disposition; filename=$fileName" ) ; 

        $sysEncoding = mb_internal_encoding(); // 現在の内部文字コードをキープ
        mb_http_output("SJIS");                // HTTP文字コードをSJISに明示的に設定
        mb_internal_encoding($sysEncoding);    // 内部文字コードを元に戻す

        return true;
    }
    /**
     * 
     * 配列からCSVテキストへ変換（一行）
     * @param     string  $rowData    行データ
     * @param     string  $delimeter  区切り文字
     * @param     string  $quote      コーテーション (" または 空白)
     * @param     string  $newLineReplace      改行変換文字列
     * @return    string
    */
    public static function arrayToLine($rowData, $delimeter = ',', $quote = '', $newLineReplace = null)
    {
        if(isset($newLineReplace)) {
            for($i=0; $i<count($rowData); $i++) {
                $rowData[$i] = preg_replace('/\r?\n/', $newLineReplace, $rowData[$i]);
            }
        }
        return $quote . implode($quote.$delimeter.$quote, $rowData) . $quote;
    }
    
    /**
     * 
     * 配列からCSVテキストへ変換
     * @param     string  $arrData    配列データ
     * @param     string  $delimeter  区切り文字
     * @param     string  $quote      コーテーション (" または 空白)
     * @param     string  $newLine    改行文字
     * @param     string  $encode     文字コード (例: SJIS-win)
     * @param     string  $fromEncode 元文字コード (例: UTF-8)
     * @return    string
    */
    public static function arrayToText($arrData, $delimeter = ',', $quote = '', $newLine = "\r\n", $encode = '', $fromEncode='auto')
    {
        $text = '';
        foreach($arrData as $i=>$row) {
            $text .= self::arrayToLine($row, $delimeter, $quote);
        }
        if($encode) {
            $text = mb_convert_encoding($text, $encode, $fromEncode);
        }
        return $text;
    }
}