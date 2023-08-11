<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Controller\RestApiClientExamples;

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

use Aoe\RestlerExamples\Domain\Model\Car;
use Aoe\RestlerExamples\Domain\Model\Manufacturer;

/**
 * Class InternalApiController
 *
 * @IgnoreAnnotation("url")
 * @IgnoreAnnotation("access")
 * @IgnoreAnnotation("class")
 * @IgnoreAnnotation("status")
 */
class InternalApiController
{
    /**
     * Internal API-Endpoint
     *
     * This internal API-endpoint is protected from outside, if production-mode is active.
     * Use Aoe\Restler\System\RestApi\RestApiClient to call this endpoint.
     *
     * @url GET internal_endpoint/cars/{id}
     * @class Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiAuthenticationController {@checkAuthentication true}
     */
    public function getCarById(int $id): Car
    {
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
     * Internal API-Endpoint
     *
     * This internal API-endpoint is protected from outside, if production-mode is active.
     * Use Aoe\Restler\System\RestApi\RestApiClient to call this endpoint.
     *
     * @url POST internal_endpoint/cars
     * @status 201
     */
    public function buyCar(Car $car): Car
    {
        return $car;
    }
}
