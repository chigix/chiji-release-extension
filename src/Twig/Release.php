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
use Chigi\Chiji\Plugin\ReleaseExtension\Util\Release as ReleaseOperation;
use Chigi\Chiji\Plugin\TwigCache\TwigCacheRoadExtensionInterface;

/**
 * The Release controller extension for Twig Cache Building.
 *
 * @author Richard Lea <chigix@zoho.com>
 */
class Release implements TwigCacheRoadExtensionInterface {

    private $resource;

    /**
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @param \Twig_Environment $environment The current Twig_Environment instance
     */
    public function initRuntime(\Twig_Environment $environment) {
        
    }

    /**
     * Returns the token parser instances to add to the existing list.
     *
     * @return array An array of Twig_TokenParserInterface or Twig_TokenParserBrokerInterface instances
     */
    public function getTokenParsers() {
        return array();
    }

    /**
     * Returns the node visitor instances to add to the existing list.
     *
     * @return \Twig_NodeVisitorInterface[] An array of Twig_NodeVisitorInterface instances
     */
    public function getNodeVisitors() {
        return array();
    }

    /**
     * Returns a list of filters to add to the existing list.
     *
     * @return array An array of filters
     */
    public function getFilters() {
        return array();
    }

    /**
     * Returns a list of tests to add to the existing list.
     *
     * @return array An array of tests
     */
    public function getTests() {
        return array();
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions() {
        return array();
    }

    /**
     * Returns a list of operators to add to the existing list.
     *
     * @return array An array of operators
     */
    public function getOperators() {
        return array();
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals() {
        return array(
            "release" => new ReleaseOperation($this->resource),
            "css" => new CssBuildingHelper($this->resource),
        );
    }

    public function getName() {
        return "release";
    }

    public function setResource(AbstractResourceFile $resource) {
        $this->resource = $resource;
    }

}
