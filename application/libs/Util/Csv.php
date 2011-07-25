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
        header( "Cache-Control: public" );
        header( "Pragma: public" );
        header( "Content-type: text/csv" ) ;
        header( "Content-Disposition: $disposition; filename=$fileName" ) ; 

        $kanji_code = mb_internal_encoding(); // 現在の内部文字コードをキープ
        mb_http_output("SJIS");             // HTTP文字コードをSJISに明示的に設定
        mb_internal_encoding($kanji_code);  // 内部文字コードを元に戻す

        return true;
    }

    /**
     * 
     * 配列からCSVテキストへ変換
     * @param     string  $arrData    配列データ
     * @param     string  $delimeter  区切り文字
     * @param     string  $newLine    改行文字
     * @param     string  $encode     文字コード
     * @param     string  $fromEncode 元文字コード
     * @return    string
    */
    public static function convertArrayToCsv($arrData, $delimeter = ',', $newLine = "\r\n", $encode = 'SJIS-win', $fromEncode='UTF-8')
    {
        $text = '';
        foreach($arrData as $i=>$row) {
            $prefix = '';
            foreach($row as $k=>$col) {
                $text .= $prefix.$col;
                $prefix = $delimeter;
            }
            $text .= $newLine;
        }
        if($encode) {
            $text = mb_convert_encoding($text, $encode, $fromEncode);
        }
        return $text;
    }

}