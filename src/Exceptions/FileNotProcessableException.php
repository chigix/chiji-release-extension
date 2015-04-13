<?php

/*
 * This file is part of the chiji-release-extension package.
 * 
 * (c) Richard Lea <chigix@zoho.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Chigi\Chiji\Plugin\ReleaseExtension\Exceptions;

/**
 * Description of FileNotProcessableException
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class FileNotProcessableException extends \Exception {
    
    /**
     * 
     * @param string $path_string
     * @param string $reason
     */
    public function __construct($path_string, $reason = "File Not Processable") {
        parent::__construct($reason . " : " . $path_string);
    }
}
