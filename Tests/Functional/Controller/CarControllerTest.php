<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Tests\Functional\Controller;

use Aoe\RestlerExamples\Domain\Model\Car;
use Aoe\RestlerExamples\Domain\Model\Manufacturer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2021 AOE GmbH <dev@aoe.com>
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

class CarControllerTest extends BaseControllerTest
{
    /**
     * @test
     */
    public function getCarsById()
    {
        $response = $this->executeFrontendRequest(
            new InternalRequest('https://acme.com/api/motorsport/cars/1/')
        );

        self::assertEquals(200, $response->getStatusCode());
        $this->assertJsonSchema(
            (string)$response->getBody(),
            $this->getJsonSchemaPath() . 'car.json'
        );
    }

    /**
     * @test
     */
    public function getInvalidCarsById()
    {
        $response = $this->executeFrontendRequest(
            new InternalRequest('https://acme.com/api/motorsport/cars/X/')
        );

        self::assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function buyCar()
    {
        self::markTestSkipped("HTTP-Method POST is not supported in typo3/testing-framework without patching");

        $car = new Car();
        $car->id = 1;
        $car->manufacturer = new Manufacturer();
        $car->manufacturer->id = 1;
        $car->manufacturer->name = 'BMW';
        $car->models = ['X1', 'X3', 'X5'];

        $response = $this->executeFrontendRequest(
            new InternalRequest(
                'https://website.local/api/motorsport/cars/',
                'POST',
                json_encode($car),
                [
                    'Content-Type' => 'application/json'
                ]
            )
        );

        $this->assertJsonSchema(
            (string)$response->getBody(),
            $this->getJsonSchemaPath() . 'car.json'
        );
        self::assertEquals(201, $response->getStatusCode());
    }

    /**
     * Defines the path where the json schema files are located.
     *
     * @return string
     */
    public function getJsonSchemaPath()
    {
        $extensionPath = ExtensionManagementUtility::extPath('restler_examples');
        return $extensionPath . '/Tests/Functional/json-schema/';
    }
}
