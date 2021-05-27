<?php
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
use Luracast\Restler\RestException;

/**
 * Class InternalApiController
 * @package Aoe\RestlerExamples\Controller\RestApiClientExamples
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
     * @access protected
     * @class Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiAuthenticationController {@checkAuthentication true}
     *
     * @param integer $id
     * @return Car {@type \Aoe\RestlerExamples\Domain\Model\Car}
     */
    public function getCarById($id)
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
     * @access protected
     * @class Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiAuthenticationController {@checkAuthentication true}
     *
     * @param Car $car {@from body} {@type \Aoe\RestlerExamples\Domain\Model\Car}
     * @return Car {@type \Aoe\RestlerExamples\Domain\Model\Car}
     * @throws RestException 400 Car is not valid
     */
    public function buyCar(Car $car)
    {
        return $car;
    }
}
