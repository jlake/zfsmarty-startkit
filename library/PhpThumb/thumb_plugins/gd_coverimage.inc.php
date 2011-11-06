<?php
/**
 * 書影画像作成 Plugin
 * 
 * 画像をリサイズして間中に表示、背景は白色にします
 * 
 * @package PhpThumb
 * @subpackage Plugins
 * @author ou
 */
class GdCoverImageLib
{
    /**
     * Instance of GdThumb passed to this class
     * 
     * @var GdThumb
     */
    protected $parentInstance;
    protected $currentDimensions;
    protected $workingImage;
    protected $oldImage;
    protected $options;
    
    public function createCoverImage($coverWidth, $coverHeight, &$that)
    {
        // bring stuff from the parent class into this class...
        $this->parentInstance       = $that;
        $this->currentDimensions    = $this->parentInstance->getCurrentDimensions();
        $this->workingImage         = $this->parentInstance->getWorkingImage();
        $this->oldImage             = $this->parentInstance->getOldImage();
        $this->options              = $this->parentInstance->getOptions();
        
        $width                      = $this->currentDimensions['width'];
        $height                     = $this->currentDimensions['height'];

        $this->workingImage = imagecreatetruecolor($coverWidth, $coverHeight);
        $colorToPaint = imagecolorallocatealpha($this->workingImage,255, 255, 255, 0);
        imagefilledrectangle($this->workingImage, 0, 0, $coverWidth, $coverHeight, $colorToPaint);

        $newX = 0;
        $newY = 0;
        if($coverWidth <= $coverHeight) {
            if($width >= $height) {
                $newWidth = $coverWidth;
                $newHeight = ceil($newWidth * $height / $width);
                $newY = ceil(($coverHeight - $newHeight) / 2);
            } else {
                $newHeight = $coverHeight;
                $newWidth = ceil($newHeight * $width / $height);
                $newX = ceil(($coverWidth - $newWidth) / 2);
            }
        } else {
            if($height >= $width) {
                $newHeight = $coverHeight;
                $newWidth = ceil($newHeight * $width / $height);
                $newX = ceil(($coverWidth - $newWidth) / 2);
            } else {
                $newWidth = $coverWidth;
                $newHeight = ceil($newWidth * $height / $width);
                $newY = ceil(($coverHeight - $newHeight) / 2);
            }
        }
        /*
        Lib_Util_Log::log('site', "width = $width; height = $height");
        Lib_Util_Log::log('site', "newWidth = $newWidth; newHeight = $newHeight");
        Lib_Util_Log::log('site', "newX = $newX; newY = $newY");
        */
        imagecopyresized($this->workingImage, $this->oldImage, $newX, $newY, 0, 0, $newWidth, $newHeight, $width, $height);

        $this->parentInstance->setOldImage($this->workingImage);
        $this->currentDimensions['width'] = $coverWidth;
        $this->currentDimensions['height'] = $coverHeight;
        $this->parentInstance->setCurrentDimensions($this->currentDimensions);

        return $that;
    }
}

$pt = PhpThumb::getInstance();
$pt->registerPlugin('GdCoverImageLib', 'gd');