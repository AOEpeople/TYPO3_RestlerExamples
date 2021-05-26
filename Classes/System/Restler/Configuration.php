<?php
namespace Aoe\RestlerExamples\System\Restler;

use Aoe\Restler\System\Restler\ConfigurationInterface;
use Aoe\RestlerExamples\Controller\BeUserController;
use Aoe\RestlerExamples\Controller\CarController;
use Aoe\RestlerExamples\Controller\ContentController;
use Aoe\RestlerExamples\Controller\ExtbaseExamples\ProductController;
use Aoe\RestlerExamples\Controller\FeUserController;
use Aoe\RestlerExamples\Controller\HttpStatusCodeController;
use Aoe\RestlerExamples\Controller\RestApiClientExamples\ExternalApiController;
use Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiAuthenticationController;
use Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiController;
use Aoe\RestlerExamples\Controller\Typo3CacheExamples\CarController as CarControllerCache;
use Luracast\Restler\Format\JsonFormat;
use Luracast\Restler\Restler;

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
 * Configure restler:
 *  - add API-classes
 *  - configure JSON-format
 *  - add event (the event is commented out, otherwise the output would be invalid...but you can see, how events work in restler)
 *
 * @package RestlerExamples
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @param Restler $restler
     * @return void
     */
    public function configureRestler(Restler $restler)
    {
        // set german as supported language
        //Defaults::$supportedLanguages = ['de'];
        //Defaults::$language = 'de';
        JsonFormat::$prettyPrint = true;

        $restler->setSupportedFormats('JsonFormat');

        $restler->addAPIClass(CarController::class, 'api/motorsport');
        $restler->addAPIClass(ContentController::class, 'api/shop');
        $restler->addAPIClass(HttpStatusCodeController::class, 'api/http-status-codes');

        $restler->addAPIClass(FeUserController::class, 'api/fe-user');
        $restler->addAPIClass(BeUserController::class, 'api/be-user');

        $restler->addAPIClass(ProductController::class, 'api/extbase-examples');
        $restler->addAPIClass(CarControllerCache::class, 'api/typo3cache-examples');

        $restler->addAPIClass(ExternalApiController::class, 'api/rest-api-client1');
        $restler->addAPIClass(InternalApiController::class, 'api/rest-api-client2');
        $restler->addAuthenticationClass(InternalApiAuthenticationController::class);

        // add exception-handler (which logs exceptions)
        $restler->addErrorClass(ExceptionHandler::class);

        // add Event (restler supports several events - those events can be used for severeal purposes)
        /*
        // This is only a TEST...If we add content at the end of the response, than the
        // response is maybe invalid and can not be displayed in the online documentation!
        $closure = function () use ($restler) {
            // Take note of the underscore ('_') to get the responseData from restler:
            // In restler, we can access protected properties by using this underscore!
            echo "\n\n";
            echo "################################################################################ \n";
            echo "Event 'onComplete' was triggered! \n";
            echo "HTTP-StatusCode: ".$restler->responseCode."\n";
            echo "String-Length of Response: " . strlen($restler->_responseData) . "\n";
            echo "################################################################################ \n";
            flush();
        };
        $restler->onComplete($closure);
        */
    }
}
