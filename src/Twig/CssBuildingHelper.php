<?php

/*
 * This file is part of the chiji-release-extension package.
 * 
 * (c) Richard Lea <chigix@zoho.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chigi\Chiji\Plugin\ReleaseExtension\Twig;

use Chigi\Chiji\File\AbstractResourceFile;
use Chigi\Chiji\Plugin\ReleaseExtension\Exceptions\FileNotProcessableException;
use Chigi\Chiji\Plugin\ReleaseExtension\Util\Pack\ImageUnit;
use Chigi\Chiji\Plugin\ReleaseExtension\Util\PackUtil;
use Chigi\Chiji\Plugin\ReleaseExtension\Util\ProjectMapping;
use Chigi\Chiji\Plugin\TwigCache\RenderFixTaskQueue;
use Chigi\Component\IO\File;

/**
 * Building Helper global object for CSS
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class CssBuildingHelper {

    /**
     *
     * @var AbstractResourceFile
     */
    private $resource;

    function __construct(AbstractResourceFile $resource) {
        $this->resource = $resource;
    }

    /**
     * 
     * @param string $imagePath
     * @param array $options
     */
    public function backgroundImage($imagePath, $options = array()) {
        $image = new File($imagePath, $this->getResource()->getFile()->getAbsoluteFile()->getParent());
        if (!$image->isFile()) {
            throw new FileNotProcessableException($imagePath);
        }
        $data = \getimagesize($image->getAbsolutePath());
        $manager = ProjectMapping::getManagerByProject($this->getResource()->getParentProject());
        $options = array_merge(array(
            'scaleWidth' => "auto",
            'scaleHeight' => "auto",
            'canvasWidth' => "auto",
            'canvasHeight' => "auto",
            "canvasPosX" => 0,
            "canvasPosY" => 0,
            'tileDirection' => "none",
            'packName' => "auto",
                ), $options);
        $origWidth = $data[0];
        $origHeight = $data[1];
        /* @var $scaleWidth int */
        $scaleWidth = $origWidth;
        if ($options['scaleWidth'] === "auto") {
            if (\preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleHeight'])) {
                $scaleWidth = (\str_replace('%', '', $options['scaleHeight']) / 100) * $origWidth;
            }
        } elseif (\preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleWidth'])) {
            $scaleWidth = (\str_replace('%', '', $options['scaleWidth']) / 100) * $origWidth;
        } elseif (\preg_match('/^\d+$/', $options["scaleWidth"])) {
            $scaleWidth = intval($options["scaleWidth"]);
        }
        if (\preg_match('/^\d+$/', $options["scaleHeight"])) {
            $scaleHeight = intval($options["scaleHeight"]);
        } elseif (\preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleHeight'])) {
            $scaleHeight = (\str_replace('%', '', $options['scaleHeight']) / 100) * $origHeight;
        } else {
            $scaleHeight = ($scaleWidth / $origWidth) * $origHeight;
        }
        $canvasWidth = $scaleWidth;
        $canvasHeight = $scaleHeight;
        if (\preg_match('/^\d+$/', $options["canvasWidth"])) {
            $canvasWidth = intval($options["canvasWidth"]);
        }
        if (\preg_match('/^\d+$/', $options["canvasHeight"])) {
            $canvasHeight = intval($options["canvasHeight"]);
        }
        if (\preg_match('/^\d+$/', $options["canvasPosX"])) {
            $canvasPosX = intval($options["canvasPosX"]);
        } elseif (strtolower($options["canvasPosX"]) === "center") {
            $canvasPosX = intval(($canvasWidth - $scaleWidth) / 2);
        } elseif (strtolower($options["canvasPosX"]) === "left") {
            $canvasPosX = 0;
        } elseif (strtolower($options["canvasPosX"]) === "right") {
            $canvasPosX = intval($canvasWidth - $scaleWidth);
        } else {
            $canvasPosX = 0;
        }
        if (\preg_match('/^\d+$/', $options["canvasPosY"])) {
            $canvasPosY = intval($options["canvasPosY"]);
        } elseif (strtolower($options["canvasPosY"]) === "center") {
            $canvasPosY = intval(($canvasHeight - $scaleHeight) / 2);
        } elseif (strtolower($options["canvasPosY"]) === "top") {
            $canvasPosY = 0;
        } elseif (strtolower($options["canvasPosY"]) === "bottom") {
            $canvasPosY = intval($canvasHeight - $scaleHeight);
        } else {
            $canvasPosY = 0;
        }
        if ($options["tileDirection"] === "none" && $options["packName"] === "auto") {
            $output_dir = ProjectMapping::getManagerByProject($this->getResource()->getParentProject())->getDefaultImageOutputDir();
            $this->getResource()->getParentProject()->getCacheManager()->registerDirectory($output_dir);
            $cache_dir = $this->getResource()->getParentProject()->getCacheManager()->searchCacheDir($output_dir);
            if (!$cache_dir->exists()) {
                $cache_dir->mkdirs();
            }
            $imageUnit = new ImageUnit($image->getAbsolutePath(), $scaleWidth, $scaleHeight, $canvasWidth, $canvasHeight, $canvasPosX, $canvasPosY);
            $imagePack = PackUtil::getImagePack(new File(md5($this->getResource()->getRealPath()) . ".png", $cache_dir->getAbsolutePath()));
            $imagePack->push($imageUnit);
        }
        $replace_code = sha1(uniqid()) . ":" . sha1($this->getResource()->getRealPath()) . ";";
        RenderFixTaskQueue::getInstance()->push(new CssImagePackFix($this->getResource(), $replace_code, $imageUnit, $imagePack));
        return $replace_code;
    }

    /**
     * 
     * @return AbstractResourceFile
     */
    function getResource() {
        return $this->resource;
    }

}
