<?php

namespace Aoe\RestlerExamples\Controller\Typo3CacheExamples;

use Aoe\Restler\System\TYPO3\Cache;
use Aoe\RestlerExamples\Domain\Model\Car;
use Aoe\RestlerExamples\Domain\Model\Manufacturer;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * @package RestlerExamples
 * @subpackage Controller
 *
 * @IgnoreAnnotation("url")
 * @IgnoreAnnotation("restler_typo3cache_expires")
 * @IgnoreAnnotation("restler_typo3cache_tags")
 */
class CarController
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Test GET-Method which will be cached for 2 Minutes
     * The 'rendering' of the response will take 5 seconds (if TYPO3-cache does not exist)
     *
     * The response of this REST-endpoint will be cached by TYPO3-caching-framework, because:
     *  - it's a GET-request/method
     *  - annotation 'restler_typo3cache_expires' (define seconds, after cache is expired; '0' means cache will never expire) is set
     *  - annotation 'restler_typo3cache_tags' (comma-separated list of cache-tags) is set
     *
     * The cache is stored in this TYPO3-tables:
     *  - cache_restler
     *  - cache_restler_tags
     *
     * @url GET cars/{id}
     *
     * @restler_typo3cache_expires 180
     * @restler_typo3cache_tags typo3cache_examples,typo3cache_example_car
     *
     * @param integer $id
     * @return Car {@type \Aoe\RestlerExamples\Domain\Model\Car}
     */
    public function getCarsById($id)
    {
        // sleep 5 seconds when TYPO3-cache is not available
        sleep(5);

        $manufacturer = new Manufacturer();
        $manufacturer->id = $id;
        $manufacturer->name = 'BMW';

        $car = new Car();
        $car->manufacturer = $manufacturer;
        $car->id = $id;
        $car->models = ['X3', 'X5', 'X7'];
        return $car;
    }

    /**
     * @url POST flush-tag
     *
     * @param string $tag {@from body}
     * @return string
     */
    public function flushCacheById($tag)
    {
        $this->cache->flushByTag($tag);
        return 'caches, which are tagged with tag "' . $tag . '", are flushed!';
    }
}
