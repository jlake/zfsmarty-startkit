<?php
class Lib_View_Helper_Thumb extends Zend_View_Helper_Abstract {
	public function thumb($imgPath, $width = 100, $height = 100) {
        $srcPath = ROOT_PATH .'/data/'.$imgPath;
        $thumbPath = THUMB_ROOT.'/'.$imgPath;
        $imgTag = '<img src="'.THUMB_BASE_URI.'/'.$imgPath.'" />';
        if(file_exists($thumbPath)) {
            return $imgTag;
        }
        
        $thumbDir = dirname($thumbPath);
        if(!file_exists($thumbDir)) {
            mkdir($thumbDir, 0666, true);
        }
        if(is_dir($thumbDir) && is_writable($thumbDir)) {
            Lib_Util_Image::createThumbnail($srcPath, $thumbPath, $width, $height);
        }
        if(!file_exists($thumbPath)) {
            $imgTag = '<!--サムネイル画像作成失敗-->';
        }
        return $imgTag;
	}
}