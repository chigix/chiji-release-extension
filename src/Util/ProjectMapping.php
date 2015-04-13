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

use Chigi\Chiji\Plugin\ReleaseExtension\Exceptions\ProjectNotRegisteredException;
use Chigi\Chiji\Plugin\ReleaseExtension\ReleaseManager;
use Chigi\Chiji\Project\Project;

/**
 * The Mapping between Project and ReleaseManager.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class ProjectMapping {

    private static $mapping = array();

    /**
     * Register a Project-ReleaseManager Mapping.
     * 
     * @param Project $project
     * @param ReleaseManager $manager
     */
    public static function register(Project $project, ReleaseManager $manager) {
        self::$mapping[$project->getProjectName()] = array($project, $manager);
    }

    /**
     * 
     * @param Project $project
     * @return ReleaseManager
     */
    public static function getManagerByProject(Project $project) {
        if (isset(self::$mapping[$project->getProjectName()])) {
            return self::$mapping[$project->getProjectName()][1];
        } else {
            throw new ProjectNotRegisteredException($project->getProjectName() . " NOT REGISTERED.");
        }
    }

}
