<?php
/**
 * ファイル処理機能
 * @author ou
 *
 */

class Lib_Util_File
{
    /**
     * ファイル一覧を取得
     * @param   int $dir   ファイルフォルダ
     * @param   array $filter   フィルタ（ファイルの種類）
     * @return   array
    **/
    public static function findFiles($dir, $filter = array('jpg', 'jpeg', 'png', 'gif')) {
        $result = array();
        $files = scandir($dir);
        foreach($files as $file){
            if($file === '.' || $file === '..') {
                continue;
            }
            $full_path = $dir.DIRECTORY_SEPARATOR.$file;
            if(is_dir($full_path)){
                $result = array_merge($result, self::find_files($full_path, $filter));
            } elseif(in_array(substr($file, strrpos($file, '.') + 1), $filter)) {
                $result[] = $full_path;
            }
        }
        return $result;
    }

    /**
     * ファイルサイズを KB, MB, GB ... で取得
     * @param   int $size   ファイルサイズ, Byte単位
     * @param   boolean $asArray   trueの場合、結果は配列で返す
     * @param   int $factor  1024 または 1000
     * @return   string
    **/
    public static function formatSize($size, $asArray = true, $factor = 1024)
    {
        $units = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $i = floor(log10($size)/log10($factor));
        $p = ($i > 1) ? 2 : 0;
        $result = array(
            'size' => round($size/pow($factor, floor($i)), $p),
            'unit' => $units[$i]
        );
        if($asArray) {
            return $result;
        }
        return $result['size'] . ' ' . $result['unit'];
    }

    /**
     * ファイル内容取得（バイナリ形式）
     *
     * @param   string  $filePath    ファイルパス
     * @param   string  $mode        モード
     * @return  void
    **/
    public static function read($filePath, $mode ='r')
    {
        /* ファイルの存在確認 */
        if (!file_exists($filePath)) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 が存在しません。');
            return '';
        }
        /* 読込できるか確認 */
        if (!is_readable($filePath)) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 の読込ができません。');
            return '';
        }
        $handle = fopen($filePath, $mode);
        $contents = fread($handle, filesize($filePath));
        fclose($handle);
        return $contents;
    }

    /** 
     * テキストファイルの作成
     *
     * @param   string  $filePath    ファイルパス
     * @param   string  $contents    コンテンツ
     * @param   string  $encoding    文字コード
     * @return  void
    */
    public static function writeTextFile($filePath, $contents, $encoding = null) {
        if(!is_writeable($filePath)) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 の書き込みができません。');
            return false;
        }
        if(isset($encoding)) {
            $contents = mb_convert_encoding($contents, $encoding, 'auto');
        }
        return file_put_contents($filePath, $contents);
    }

    /**
     * ファイルダウンロード
     *
     * @param   string  $filePath        ファイルパス
     * @return  void
    **/
    public static function download($filePath)
    {
        /* ファイルの存在確認 */
        if (!file_exists($filePath)) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 が存在しません。');
            return;
        }

        /* 読込できるか確認 */
        if (!is_readable($filePath)) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 の読込ができません。');
            return;
        }

        /* ファイルサイズの確認 */
        $contentLength = filesize($filePath);
        if ($contentLength == 0) {
            throw new Exception('エラー: ファイル 「'. $filePath .'」 のサイズが0です。');
            return;
        }

        header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$contentLength);
        readfile($filePath);
    }

    /**
     * ファイルアップロード
     *
     * @param   string  $inputName        <input type="file" ...> タグの name 属性値
     * @param   string  $destPath         保存先パス
     * @param   number  $maxSize          ファイル最大サイズ, 単位：kb [int/float]
     * @param   string  $newFileName      アップロード後のファイル名(空なら、元ファイル名のままで保存)
     * @param   string  $allowTypes       許可するファイル拡張子(小文字)，"|"で区切り
     * @param   string  $refuseTypes      拒否するファイル拡張子(小文字)，"|"で区切り
     * @param   string  $mode             ファイル権限
     * @return  string                    アップロード結果。成功: '', 失敗: 'エラー内容文字列'
    **/
    public static function upload($inputName, $destPath, $maxSize=2048, $newFileName='', $allowTypes='', $refuseTypes='.php|.jsp|.pl|.sh|.csh|.asp|.exe')
    {
        $uploadInfo =& $_FILES[$inputName];

        if(!empty($uploadInfo['error'])) {
            switch($uploadInfo['error']) {
                case '1':
                    return 'ファイルサイズが php.ini の upload_max_filesize 設定値に超えています。';
                    break;
                case '2':
                    return 'ファイルサイズが HTML form の MAX_FILE_SIZE 設定値に超えています。';
                    break;
                case '3':
                    return 'ファイルの一部だけアップされました。';
                    break;
                case '4':
                    return 'ファイルがアップされませんでした。';
                    break;
                case '6':
                    return 'ファイルアップ用一時フォルダが見つかりません。';
                    break;
                case '7':
                    return 'ファイルの保存が失敗。';
                    break;
                case '8':
                    return '拡張子によるファイルのアップが中止。';
                    break;
                case '999':
                default:
                    break;
            }
        }

        $uploadName = $uploadInfo['name'];

        //--ディレクトリ一気に作成*
        if(!file_exists($destPath)) {
            //system("mkdir -p $destPath");
            mkdir($destPath, 0775, true);
        }

        if (!is_writeable($destPath)) {
            return "ディレクトリ \"$destPath\" に書き込みできません。\nシステム管理者にお問い合わせ下さい。";
        }

        if (!is_uploaded_file($uploadInfo['tmp_name'])) {
            return "指定したファイル（\"".$uploadName."\"）が不正です。\nシステム管理者にお問い合わせ下さい。";
        }

        if ($maxSize > 0 && $uploadInfo['size']/1024 > $maxSize) {
            return 'ファイルサイズが制限値を超えています。(制限値: ' . $maxSize . 'kb)';
        }

        $fileExt = strtolower(strrchr($uploadName, "."));

        //拡張子許可チェック*
        if($fileExt == '' && !empty($allowTypes)) {
            $allowTypes = ereg_replace("\|","または",$allowTypes);
            return "ファイルタイプが正しくありません。".$allowTypes."ファイルをアップロードして下さい。";
        }
        if (!empty($allowTypes)    && !strpos("|" . $allowTypes, $fileExt)) {
            $allowTypes = ereg_replace("\|","または",$allowTypes);
            return "ファイルタイプ($fileExt)が正しくありません。".$allowTypes."ファイルをアップロードして下さい。";
        }

        //拡張子禁止チェック*
        if (strpos("|" . $refuseTypes, $fileExt)) {
            $refuseTypes = ereg_replace("\|","または",$refuseTypes);
            return "ファイルタイプ($fileExt)が正しくありません。".$refuseTypes."以外のファイルをアップロードして下さい。";
        }

        if(!empty($newFileName)) {
            //$fileName = $newFileName . '.' . pathinfo($uploadName, PATHINFO_EXTENSION);
            $fileName = $newFileName;
        } else {
            $fileName = $uploadName;
        }

        $fullPath = "$destPath/$fileName";
        if (!move_uploaded_file($uploadInfo['tmp_name'], $fullPath)) {
            return "ファイルのアップロードうまくできませんでした!";
        }
        return '';
    }
}