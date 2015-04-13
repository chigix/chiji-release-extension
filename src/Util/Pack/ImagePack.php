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

use Chigi\Component\IO\File;

/**
 * Pack Util For Image
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class ImagePack {

    private $outputFile;

    /**
     *
     * @var ImageUnit[]
     */
    private $units;
    private $pushedUnitCodes;
    private $positions;

    /**
     * 
     * @param File $outputFile
     */
    function __construct(File $outputFile) {
        $this->outputFile = $outputFile;
        $this->units = array();
        $this->pushedUnitCodes = array();
        $this->positions = array();
    }

    /**
     * Output the rendered Pack of images.
     */
    public function output() {
        $this->calculate();
        $image = \imagecreatetruecolor($this->__pack_width, $this->__pack_height);
        \imagealphablending($image, true);
        \imagesavealpha($image, true);
        \imagefill($image, 0, 0, \imagecolorallocatealpha($image, 0, 0, 0, 127));
        foreach ($this->units as $imageUnit) {
            \imagecopy($image, $imageUnit->generateUnitResource(), $this->positions[$imageUnit->hashCode()][0], $this->positions[$imageUnit->hashCode()][1], 0, 0, $imageUnit->getCanvasWidth(), $imageUnit->getCanvasHeight());
        }
        \imagepng($image, $this->outputFile->getAbsolutePath(), 9);
    }

    private $__last_output_file_sha1 = "";

    public function push(ImageUnit $unit) {
        if (!in_array($unit->hashCode(), $this->pushedUnitCodes)) {
            array_push($this->units, $unit);
            array_push($this->pushedUnitCodes, $unit->hashCode());
        }
    }

    private $__last_calc_pushedUnitCodes = array();
    private $__pack_width = 0;
    private $__pack_height = 0;

    public function calculate() {
        if (\sha1(\json_encode($this->__last_calc_pushedUnitCodes)) === \sha1(\json_encode($this->pushedUnitCodes))) {
            return;
        }
        foreach ($this->units as $imageUnit) {
            if ($imageUnit->getCanvasWidth() > $this->__pack_width) {
                $this->__pack_width = $imageUnit->getCanvasWidth();
            }
            $this->positions[$imageUnit->hashCode()] = array(0, $this->__pack_height);
            $this->__pack_height += $imageUnit->getCanvasHeight();
        }
        $this->__last_calc_pushedUnitCodes = $this->pushedUnitCodes;
    }

    /**
     * 
     * @param ImageUnit $imageUnit
     * @return int[] [X,Y]
     */
    public function getPositionByUnit(ImageUnit $imageUnit) {
        $this->calculate();
        return $this->positions[$imageUnit->hashCode()];
    }

    /**
     * 
     * @return File
     */
    function getOutputFile() {
        return $this->outputFile;
    }

}
