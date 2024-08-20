<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Tests\Functional\Controller;

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
    public function testGetCarsById(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://acme.com/api/motorsport/cars/1/')
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonSchema(
            (string) $response->getBody(),
            $this->getJsonSchemaPath() . 'car.json'
        );
    }

    public function testGetInvalidCarsById(): void
    {
        $response = $this->executeFrontendSubRequest(
            new InternalRequest('https://acme.com/api/motorsport/cars/X/')
        );

        $this->assertSame(400, $response->getStatusCode());
    }

    public function getJsonSchemaPath(): string
    {
        $extensionPath = ExtensionManagementUtility::extPath('restler_examples');
        return $extensionPath . '/Tests/Functional/json-schema/';
    }
}
