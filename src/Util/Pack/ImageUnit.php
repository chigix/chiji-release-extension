<?php

/*
 * This file is part of the chiji-release-extension package.
 * 
 * (c) Richard Lea <chigix@zoho.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chigi\Chiji\Plugin\ReleaseExtension\Util\Pack;

use Chigi\Chiji\Plugin\ReleaseExtension\Exceptions\FileNotProcessableException;

/**
 * Description of ImageUnit
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class ImageUnit {

    private $Path;
    private $origWidth;
    private $origHeight;
    private $scaleWidth;
    private $scaleHeight;
    private $canvasWidth;
    private $canvasHeight;
    private $canvasPosX;
    private $canvasPosY;
    private $mime;

    /**
     * 
     * @param string $Path
     * @param int $scaleWidth
     * @param int $scaleHeight
     * @param int $canvasWidth
     * @param int $canvasHeight
     * @param int $canvasPosX
     * @param int $canvasPosY
     */
    function __construct($Path, $scaleWidth, $scaleHeight, $canvasWidth, $canvasHeight, $canvasPosX = 0, $canvasPosY = 0) {
        $this->Path = $Path;
        $this->scaleWidth = $scaleWidth;
        $this->scaleHeight = $scaleHeight;
        $this->canvasWidth = $canvasWidth;
        $this->canvasHeight = $canvasHeight;
        $this->canvasPosX = $canvasPosX;
        $this->canvasPosY = $canvasPosY;
        $this->analyze();
    }

    /**
     * 
     * @return string The Image File Absolute Path.
     */
    function getPath() {
        return $this->Path;
    }

    /**
     * 
     * @return int The Width length in Pixel.
     */
    function getScaleWidth() {
        return $this->scaleWidth;
    }

    /**
     * 
     * @return int The Height Length in pixel.
     */
    function getScaleHeight() {
        return $this->scaleHeight;
    }

    /**
     * 
     * @return int The Width length in pixel.
     */
    function getCanvasWidth() {
        return $this->canvasWidth;
    }

    /**
     * 
     * @return int The height length in pixel.
     */
    function getCanvasHeight() {
        return $this->canvasHeight;
    }

    public function hashCode() {
        return sha1(json_encode(array(
            $this->Path,
            $this->canvasWidth,
            $this->canvasHeight,
            $this->scaleWidth,
            $this->scaleHeight,
            $this->canvasPosX,
            $this->canvasPosY)
        ));
    }

    /**
     * @return resource
     */
    public function generateUnitResource() {
        if (!is_null($this->__generated)) {
            return $this->__generated;
        }
        switch ($this->mime) {
            case "image/gif":
                $image = \imagecreatefromgif($this->Path);
                break;
            case "image/jpeg":
                $image = \imagecreatefromjpeg($this->Path);
                break;
            case "image/png":
                $image = \imagecreatefrompng($this->Path);
                break;
            case "image/vnd.wap.wbmp":
                $image = \imagecreatefromwbmp($this->Path);
                break;
            case "image/xbm":
                $image = \imagecreatefromxbm($this->Path);
                break;
            default:
                throw new FileNotProcessableException($this->Path, "Unprocessable Image File");
        }
        $canvas = \imagecreatetruecolor($this->getCanvasWidth(), $this->getCanvasHeight());
        \imagealphablending($canvas, true);
        \imagefill($canvas, 0, 0, \imagecolorallocatealpha($canvas, 0, 0, 0, 127));
//        \imagesavealpha($canvas, true);
        \imagecopyresampled($canvas, $image, $this->canvasPosX, $this->canvasPosY, 0, 0, $this->getScaleWidth(), $this->getScaleHeight(), $this->origWidth, $this->origHeight);
        $this->__generated = $canvas;
        return $canvas;
    }

    private function analyze() {
        $data = \getimagesize($this->Path);
        $this->origWidth = $data[0];
        $this->origHeight = $data[1];
        $this->mime = \image_type_to_mime_type($data[2]);
    }

    private $__generated = null;

}
