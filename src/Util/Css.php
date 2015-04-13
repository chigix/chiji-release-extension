<?php

/*
 * This file is part of the chiji-release-extension package.
 * 
 * (c) Richard Lea <chigix@zoho.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chigi\Chiji\Plugin\ReleaseExtension\Util;

use Chigi\Component\IO\File;

/**
 * Release Utils for CSS.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class Css extends Release {

    /**
     * Generate the Css Style Code for target background image.
     * 
     * @param string $imagePath
     * @param array $options
     */
    public function backgroundImage($imagePath, $options = array()) {
        $image = new File($imagePath, $this->getResource()->getFile()->getAbsoluteFile()->getParent());
        $data = \getimagesize($image->getAbsolutePath());
        $manager = ProjectMapping::getManagerByProject($this->getResource()->getParentProject());
        $options = array_merge(array(
            'scaleWidth' => "auto",
            'scaleHeight' => "auto",
            'canvasWidth' => "auto",
            'canvasHeight' => "auto",
            'tileDirection' => "none",
            'packName' => "auto",
                ), $options);
        $origWidth = $data[0];
        $origHeight = $data[1];
        $mime = \image_type_to_mime_type($data[2]);
        /* @var $scaleWidth int */
        $scaleWidth = $origWidth;
        if ($options['scaleWidth'] === "auto") {
            if (preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleHeight'])) {
                $scaleWidth = (\str_replace('%', '', $options['scaleHeight']) / 100) * $origWidth;
            }
        } elseif (preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleWidth'])) {
            $scaleWidth = (\str_replace('%', '', $options['scaleWidth']) / 100) * $origWidth;
        } elseif (preg_match('/^\d+$/', $options["scaleWidth"])) {
            $scaleWidth = intval($options["scaleWidth"]);
        }
        if (preg_match('/^\d+$/', $options["scaleHeight"])) {
            $scaleHeight = intval($options["scaleHeight"]);
        } elseif (preg_match('/^\d+(\.\d+){0,1}%$/', $options['scaleHeight'])) {
            $scaleHeight = (\str_replace('%', '', $options['scaleHeight']) / 100) * $origHeight;
        } else {
            $scaleHeight = ($scaleWidth / $origWidth) * $origHeight;
        }
        $canvasWidth = $scaleWidth;
        $canvasHeight = $scaleHeight;
        if (preg_match('/^\d+$/', $options["canvasWidth"])) {
            $canvasWidth = intval($options["canvasWidth"]);
        }
        if (preg_match('/^\d+$/', $options["canvasHeight"])) {
            $canvasHeight = intval($options["canvasHeight"]);
        }
        if ($options["tileDirection"] === "none" && $options["packName"] === "auto") {
            //
        }
        $this->getResource()->getParentProject()->getCacheManager()->searchCacheDir($manager->getDefaultImageOutputDir());
        var_dump($origWidth);
        var_dump($origHeight);
        var_dump($mime);
        var_dump($image->getAbsolutePath());
        var_dump($options["scaleWidth"]);
        var_dump($options["scaleHeight"]);
        var_dump($options["canvasWidth"]);
        var_dump($options["canvasHeight"]);
        var_dump($options["tileDirection"]);
        var_dump($options["packName"]);
        exit;
    }

}
