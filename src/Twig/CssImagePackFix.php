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
use Chigi\Chiji\Plugin\ReleaseExtension\Util\Pack\ImagePack;
use Chigi\Chiji\Plugin\ReleaseExtension\Util\Pack\ImageUnit;
use Chigi\Chiji\Plugin\TwigCache\RenderFixTask;

/**
 * Fix Task Unit for Css Resource created from CssBuildingHelper.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class CssImagePackFix implements RenderFixTask {

    private $replaceCode;
    private $resource;

    /**
     *
     * @var ImageUnit
     */
    private $imageUnit;

    /**
     *
     * @var ImagePack
     */
    private $imagePack;

    /**
     *
     * @var string[]
     */
    private static $outputed = array();

    /**
     * 
     * @param AbstractResourceFile $resource
     * @param string $replaceCode
     * @param ImageUnit $imageUnit
     * @param ImagePack $pack
     */
    function __construct(AbstractResourceFile $resource, $replaceCode, ImageUnit $imageUnit, ImagePack $pack) {
        $this->resource = $resource;
        $this->replaceCode = $replaceCode;
        $this->imageUnit = $imageUnit;
        $this->imagePack = $pack;
    }

    public function execute($renderStr) {
        $this->imagePack->calculate();
        if (!\in_array(\md5($this->imagePack->getOutputFile()->getAbsolutePath()), self::$outputed)) {
            \array_push(self::$outputed, \md5($this->imagePack->getOutputFile()->getAbsolutePath()));
            $this->imagePack->output();
        }
        $replace_code_imagepath = sha1(uniqid());
        $real_imageoutput_path = $this->imagePack->getOutputFile()->getAbsolutePath();
        $real_imageoutput_position = $this->imagePack->getPositionByUnit($this->imageUnit);
        $css_code = "";
        $css_code .= "/* @pathfixall $replace_code_imagepath with $real_imageoutput_path */\n";
        $css_code .= "/* @Chigi\Chiji\Annotation\Release(path=\"$real_imageoutput_path\") */\n";
        $css_code .= "background-image: url(\"$replace_code_imagepath\");\n";
        $css_code .= "background-position: " . $real_imageoutput_position[0] . "px -" . $real_imageoutput_position[1] . "px;\n";
        $css_code .= "background-repeat: no-repeat;";
        return str_replace($this->replaceCode, $css_code, $renderStr);
    }

    public function getResource() {
        return $this->resource;
    }

}
