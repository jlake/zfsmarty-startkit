<?php

class Lib_View_Helper_Thumb extends Zend_View_Helper_Abstract {
    public function thumb($imgPath, $width = 100, $height = 100) {
        $srcPath = ROOT_PATH .'/data/'.$imgPath;
        $thumbSubDir = dirname($imgPath).'/'.$width.'x'.$height;
        $imgName = '/public/images/thumb/' . basename($imgPath);
        $destPath = self::THUMB_ROOT.'/'.$imgName;
        $imgTag = '<img src="/images/thumb/'.$imgName.'" />';
        if(!file_exists($destPath)) {
            $thumbDir = dirname($destPath);
            if(!file_exists($thumbDir)) {
                mkdir($thumbDir, 0666, true);
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