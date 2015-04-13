<?php

/*
 * This file is part of the chiji-release-extension package.
 * 
 * (c) Richard Lea <chigix@zoho.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chigi\Chiji\Plugin\ReleaseExtension;

/**
 * The project config extension interface for Release Manager Configuration.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
interface ConfigureReleaseInterface {

    /**
     * The UrlMapping for filesystem path of this project
     * 
     * @param array<UrlMapping> $mapping The Array with UrlMappings.
     */
    public function configureUrlMapping($mapping);
}
