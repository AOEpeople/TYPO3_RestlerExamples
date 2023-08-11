<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Controller;

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
 * @IgnoreAnnotation("url")
 * @IgnoreAnnotation("access")
 * @IgnoreAnnotation("class")
 * @IgnoreAnnotation("status")
 */
class CarController
{
    /**
     * Test GET-Method with public REST-API-endpoint
     *
     * API-Endpoint is always callable
     *
     * @url GET cars
     *
     * @return array {@type \Aoe\RestlerExamples\Domain\Model\Car}
     */
    public function getCars()
    {
        $manufacturer1 = new Manufacturer();
        $manufacturer1->id = 1;
        $manufacturer1->name = 'BMW';

        $car1 = new Car();
        $car1->manufacturer = $manufacturer1;
        $car1->id = 1;
        $car1->models = ['X3', 'X5', 'X7'];

        $manufacturer2 = new Manufacturer();
        $manufacturer2->id = 2;
        $manufacturer2->name = 'Audi';

        $car2 = new Car();
        $car2->manufacturer = $manufacturer2;
        $car2->id = 2;
        $car2->models = ['A1', 'A3', 'A6'];

        return [$car1, $car2];
    }

    /**
     * Test GET-Method with public REST-API-endpoint
     *
     * API-Endpoint is always callable
     *
     * @url GET cars/{id}
     */
    public function getCarsById(int $id): Car
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
     * Test GET-Method with protected REST-API-endpoint
     *
     * API-Endpoint is only callable, when TYPO3-Frontend-user is logged-in
     *
     * @url GET cars/{id}/customer/self
     * @class Aoe\Restler\Controller\FeUserAuthenticationController {@checkAuthentication true}
     */
    public function getCarsByIdForLoggedInCustomer(int $id): Car
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
     * Test POST-Request with automatic validation
     *
     * We use the automatic validation of restler, so the properties (of our custom domain-model-objects) contain the validation-rules
     *
     * @url POST cars
     * @status 201
     */
    public function buyCar(Car $car): Car
    {
        return $car;
    }
}
