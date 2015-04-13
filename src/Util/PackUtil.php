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

use Chigi\Chiji\Plugin\ReleaseExtension\Util\Pack\ImagePack;
use Chigi\Component\IO\File;

/**
 * Pack Utils
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class PackUtil {

    /**
     *
     * @var {String:Object}
     */
    private static $packs = array();

    /**
     * Get the target ImagePack via output file, 
     * which will auto be generated if not registered.
     * 
     * @param File $outputFile
     * @return ImagePack
     */
    public static function getImagePack(File $outputFile) {
        if (!\array_key_exists(\md5($outputFile->getAbsolutePath()), self::$packs)) {
            self::$packs[md5($outputFile->getAbsolutePath())] = new ImagePack($outputFile);
        }
        return self::$packs[md5($outputFile->getAbsolutePath())];
    }

}
