<?php
namespace Aoe\RestlerExamples\Tests\Functional\Controller;

use Aoe\RestlerExamples\Domain\Model\Car;
use Aoe\RestlerExamples\Domain\Model\Manufacturer;
use Aoe\RestlerExamples\Tests\Functional\Controller\BaseControllerTest;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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

class CarControllerTest extends BaseControllerTest
{
    /**
     * set up car controller test
     */
    public function setUp()
    {
        parent::setUp();

        $this->client->setSslVerification(false);
        $this->client->setBaseUrl('http://www.example.com/api/');
    }

    /**
     * @test
     */
    public function getCarsById()
    {
        $response = $this->client->get('motorsport/cars/1/')->send();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonSchema(
            $response->getBody(true),
            $this->getJsonSchemaPath() . 'car.json'
        );
    }

    /**
     * @test
     */
    public function buyCar()
    {
        $car = new Car();
        $car->id = 1;
        $car->manufacturer = new Manufacturer();
        $car->manufacturer->id = 1;
        $car->manufacturer->name = 'BMW';
        $car->models = array('X1', 'X3', 'X5');

        $response = $this->client
            ->post('motorsport/cars/', null, json_encode($car))
            ->send();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJsonSchema(
            $response->getBody(true),
            $this->getJsonSchemaPath() . 'car.json'
        );
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
