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

use Chigi\Chiji\File\AbstractResourceFile;

/**
 * The Release Util with operations.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class Release {

    private $resource;

    function __construct(AbstractResourceFile $resource) {
        $this->resource = $resource;
    }

    public function testPrint($text) {
        return $text . md5(time());
    }
    
    /**
     * 
     * @return AbstractResourceFile
     */
    protected function getResource() {
        return $this->resource;
    }

}
