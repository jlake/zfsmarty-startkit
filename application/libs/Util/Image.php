<?php
/**
 * 画像処理機能
 * require:
 *   PHP Thumbnailer Class v2.0
 * @author ou
 *
 */
require_once 'PhpThumb/ThumbLib.inc.php';
defined('DEFAULT_THUMBLIB_IMPLEMENTATION')
    || define('DEFAULT_THUMBLIB_IMPLEMENTATION', 'gd');

class Lib_Util_Image
{
    /**
     * サムネイル画像作成
     *
     * @param   string  $srcPath  元画像パス
     * @param   string  $destPath  サムネイル画像パス
     * @param   number  $w  サムネイル画像横サイズ
     * @param   number  $h  サムネイル画像縦サイズ
     */
    public static function createThumbnail($srcPath, $destPath, $w, $h)
    {
        $thumb = PhpThumbFactory::create($srcPath);
        $thumb->resize($w, $h);
        $thumb->save($destPath);
    }

    /**
     * サムネイル画像の取得（文字列）
     *
     * @param   string  $srcPath  元画像パス
     * @param   number  $w  サムネイル画像横サイズ
     * @param   number  $h  サムネイル画像縦サイズ
     */
    public static function getThumbnailAsString($srcPath, $w, $h)
    {
        $thumb = PhpThumbFactory::create($srcPath);
        $thumb->resize($w, $h);

        return $thumb->getImageAsString();
    }

    /**
     * 画像を表示
     *
     * @param   string  $srcPath  元画像パス
     * @param   number  $w  画像横サイズ(リサイズの場合指定)
     * @param   number  $h  画像縦サイズ(リサイズの場合指定)
     */
    public static function displayImage($srcPath, $w = 0, $h = 0)
    {
        $thumb = PhpThumbFactory::create($srcPath);
        if($w > 0 || $h > 0) {
            $thumb->resize($w, $h);
        }
        $thumb->show();
    }
    
    /**
     * 画像をadaptive resizeで表示
     *
     * @param   string  $srcPath  元画像パス
     * @param   number  $w  画像横サイズ(リサイズの場合指定)
     * @param   number  $h  画像縦サイズ(リサイズの場合指定)
     */
    public static function displayImageAdaptive($srcPath, $w = 0, $h = 0)
    {
        $thumb = PhpThumbFactory::create($srcPath);
        if($w > 0 || $h > 0) {
            $thumb->adaptiveResize($w, $h);
        }
        $thumb->show();
    }    

    /**
     * 書影画像をリサイズして表示
     *
     * @param   string  $srcPath  元画像パス
     * @param   number  $w  画像横サイズ(リサイズの場合指定)
     * @param   number  $h  画像縦サイズ(リサイズの場合指定)
     */
    function showCoverImage($srcPath, $w = 0, $h = 0) {
        $thumb = PhpThumbFactory::create($srcPath);
        if($w > 0 || $h > 0) {
            $thumb->createCoverImage($w, $h);
        }
        $thumb->show();
    }

    /**
     * 書影画像の取得（文字列）
     *
     * @param   string  $srcPath  元画像パス
     * @param   number  $w  画像横サイズ(リサイズの場合指定)
     * @param   number  $h  画像縦サイズ(リサイズの場合指定)
     */
    function getCoverImageAsString($srcPath, $w = 0, $h = 0) {
        $thumb = PhpThumbFactory::create($srcPath);
        if($w > 0 || $h > 0) {
            $thumb->createCoverImage($w, $h);
        }
        $thumb->getImageAsString();
    }
}