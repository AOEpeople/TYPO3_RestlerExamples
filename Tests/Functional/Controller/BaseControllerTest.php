<?php

declare(strict_types=1);

namespace Aoe\RestlerExamples\Tests\Functional\Controller;

use TYPO3\CMS\Core\Tests\Functional\SiteHandling\SiteBasedTestTrait;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use JsonSchema\Validator;

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

abstract class BaseControllerTest extends FunctionalTestCase
{
    use SiteBasedTestTrait;

    protected $testExtensionsToLoad = [
        'typo3conf/ext/restler',
        'typo3conf/ext/restler_examples',
    ];

    protected const LANGUAGE_PRESETS = [
        'EN' => ['id' => 0, 'title' => 'English', 'locale' => 'en_US.UTF8', 'iso' => 'en', 'hrefLang' => 'en-US', 'direction' => ''],
    ];

    /**
     * set up objects
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->importDataSet(__DIR__ . '/../Fixtures/pages.xml');

        $this->writeSiteConfiguration(
            'acme-com',
            $this->buildSiteConfiguration(1, 'https://acme.com/'),
            [
                $this->buildDefaultLanguageConfiguration('EN', '/'),
            ]
        );
    }

    protected function assertJsonSchema(string $jsonString, string $jsonSchemaFile): void
    {
        $data = json_decode($jsonString);

        $validator = new Validator();
        $validator->validate($data, (object)['$ref' => 'file://' . realpath($jsonSchemaFile)]);
        if (false === $validator->isValid()) {
            foreach ($validator->getErrors() as $error) {
                self::fail(sprintf('Property "%s" is not valid: %s', $error['property'], $error['message']));
            }
        } else {
            self::assertTrue(true);
        }
    }
}
