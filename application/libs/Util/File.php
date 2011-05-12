<?php
/**
 * ファイル処理機能
 * @author ou
 *
 */

class Lib_Util_File {
    /**
     * ファイルアップロード
     *
     * @param   string  $inputName        <input type="file" ...> タグの name 属性値
     * @param   string  $destPath         保存先パス
     * @param   number  $maxSize          ファイル最大サイズ, 単位：kb [integer/float]
     * @param   string  $newFileName      アップロード後のファイル名(空なら、元ファイル名のままで保存)
     * @param   string  $allowTypes       許可するファイル拡張子(小文字)，"|"で区切り
     * @param   string  $refuseTypes      拒否するファイル拡張子(小文字)，"|"で区切り
     * @param   string  $mode             ファイル権限
     * @return  string                    アップロード結果。成功: '', 失敗: 'エラー内容文字列'
    **/
    public static function upload($inputName, $destPath, $maxSize=2048, $newFileName='', $allowTypes='', $refuseTypes='.php|.jsp|.asp|.exe|.pl|.sh|.csh')
    {
        $uploadInfo =& $_FILES["$inputName"];
    
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