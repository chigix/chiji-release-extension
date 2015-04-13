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

use Chigi\Chiji\Exception\InvalidConfigException;
use Chigi\Chiji\Plugin\ReleaseExtension\Util\ProjectMapping;
use Chigi\Chiji\Project\AbstractExtension;
use Chigi\Chiji\Project\Project;
use Chigi\Chiji\Project\ProjectConfig;
use Chigi\Component\IO\File;

/**
 * Description of ReleaseManager
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class ReleaseManager extends AbstractExtension {

    private $urlMapping;
    private $defaultImageOutputDir;

    public function __construct() {
        $this->urlMapping = array();
        $this->defaultImageOutputDir = null;
    }

    /**
     * 
     * @param File $defaultImageOutputDir
     * @return ReleaseManager
     */
    function setDefaultImageOutputDir(File $defaultImageOutputDir) {
        $this->defaultImageOutputDir = $defaultImageOutputDir;
        return $this;
    }

    /**
     * 
     * @return File
     */
    public function getDefaultImageOutputDir() {
        return $this->defaultImageOutputDir;
    }

    public function onConfigure(ProjectConfig $config) {
        if ($config instanceof ConfigureReleaseInterface) {
            $config->configureUrlMapping($this->urlMapping);
        }
        if (is_null($this->defaultImageOutputDir) || !$this->defaultImageOutputDir->isDirectory()) {
            throw new InvalidConfigException("Release Manager Can't detect default image directory, Please give specification.");
        }
    }

    public function onRegister(Project $project) {
        ProjectMapping::register($project, $this);
        if (is_null($this->defaultImageOutputDir)) {
            $this->defaultImageOutputDir = new File("images", $project->getRootPath());
        }
    }

}
