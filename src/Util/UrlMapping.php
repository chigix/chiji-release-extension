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

use Chigi\Chiji\Project\SourceRoad;

/**
 * The Mapping Entry for public url and local filesystem path.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class UrlMapping {

    /**
     *
     * @var string
     */
    private $url;

    /**
     *
     * @var SourceRoad
     */
    private $road;

    function __construct($url, SourceRoad $road) {
        $this->url = $url;
        $this->road = $road;
    }

    function getUrl() {
        return $this->url;
    }

    function getRoad() {
        return $this->road;
    }

}
