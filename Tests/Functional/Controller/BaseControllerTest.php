<?php
namespace Aoe\RestlerExamples\Tests\Functional\Controller;

use GuzzleHttp\Client;
use JsonSchema\Validator;
use Nimut\TestingFramework\TestCase\FunctionalTestCase;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 AOE GmbH <dev@aoe.com>
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

abstract class BaseControllerTest extends FunctionalTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $jsonString
     * @param string $jsonSchemaFile
     */
    protected function assertJsonSchema($jsonString, $jsonSchemaFile)
    {
        $data = json_decode($jsonString);

        $validator = new Validator;
        $validator->validate($data, (object)['$ref' => 'file://' . $jsonSchemaFile]);


        if (false === $validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                $this->fail(sprintf('Property "%s" is not valid: %s', $error['property'], $error['message']));
            }
        } else {
            $this->assertTrue(true);
        }
    }
}
