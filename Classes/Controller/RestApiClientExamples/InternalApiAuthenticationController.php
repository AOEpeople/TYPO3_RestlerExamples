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

use Luracast\Restler\iAuthenticate;
use Aoe\Restler\System\RestApi\RestApiClient;

/**
 * This class checks, if class Aoe\Restler\System\Restler\RestApiClient is executing an REST-API-request
 */
class InternalApiAuthenticationController implements iAuthenticate
{
    /**
     * This property defines (when it's set), that this controller should check that
     * class Aoe\Restler\System\Restler\RestApiClient is executing an REST-API-request
     * This property will be automatically set by restler, when in the API-class/controller this
     * is configured (in PHPdoc/annotations)
     *
     * Where do we set this property?
     * When the property should be used, than it must be set inside the PHPdoc-comment of the API-class-method,
     * which handle the API-request
     *
     * Syntax:
     * The PHPdoc-comment must look like this:
     * @class [className] {@[propertyName] [propertyValue]}
     *
     * Example:
     * When this controller should be used for authentication-checks, than the PHPdoc-comment must look like this:
     * @class Aoe\RestlerExamples\Controller\RestApiClientExamples\InternalApiAuthenticationController {@checkAuthentication true}
     *
     * @var boolean
     */
    public $checkAuthentication = false;

    /**
     * @var RestApiClient
     */
    private $restApiClient;

    /**
     * @param RestApiClient $restApiClient
     */
    public function __construct(RestApiClient $restApiClient)
    {
        $this->restApiClient = $restApiClient;
    }

    /**
     * This method checks, if client is allowed to access the requested (internal) API-class
     *
     * @return boolean
     */
    public function __isAllowed()
    {
        if ($this->checkAuthentication !== true) {
            // this controller is not responsible for the authentication
            return false;
        }

        if ($this->restApiClient->isProductionContextSet() && false === $this->restApiClient->isExecutingRequest()) {
            // on production, it's not allowed to call an internal REST-API from 'outside' (via e.g. browser)
            return false;
        }

        // on none-production, it's allowed to call an internal REST-API from 'outside' (via e.g. browser - to test/check the REST-API)
        // on none-production and production, it's allowed to call an internal REST-API via Aoe\Restler\System\RestApi\RestApiClient
        return true;
    }

    /**
     * return dummy string, because we DON'T need that for our case (we use NO base-authentification via REST-API)
     *
     * @return string
     * @see Luracast\Restler\iAuthenticate
     */
    public function __getWWWAuthenticateString()
    {
        return '';
    }
}
