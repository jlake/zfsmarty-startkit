<?php

class Lib_View_Helper_Thumb extends Zend_View_Helper_Abstract {
    public function thumb($imgPath, $width = 100, $height = 100) {
        $srcPath = ROOT_PATH . '/data/contents/'.$imgPath;
        $thumbSubDir = dirname($imgPath).'/'.$width.'x'.$height;
        $imgName = basename($imgPath);
        $destPath = ROOT_PATH . '/data/contents/thumb/'.$thumbSubDir.'/'.$imgName;
        $imgTag = '<img src="'. REWRITE_BASE . '/img/contents/thumb/'.$thumbSubDir.'/'.$imgName.'" />';
        if(!file_exists($destPath)) {
            $thumbDir = dirname($destPath);
            if(!file_exists($thumbDir)) {
            	error_log($thumbDir);
                mkdir($thumbDir, 0777, true);
            }
            if(is_dir($thumbDir) && is_writable($thumbDir)) {
                Lib_Util_Image::createThumbnail($srcPath, $destPath, $width, $height);
            }
            if(!file_exists($destPath)) {
                $imgTag = '<!--サムネイル画像作成失敗-->';
            }
        }
        return $imgTag;
    }
}