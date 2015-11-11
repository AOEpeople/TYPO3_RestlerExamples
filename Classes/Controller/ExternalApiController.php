<?php
namespace Aoe\RestlerExamples\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 AOE GmbH <dev@aoe.com>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Aoe\EftRestApi\Controller\AbstractController;
use Aoe\Restler\System\RestApi\RestApiClient;
use Aoe\Restler\System\RestApi\RestApiRequestException;

class ExternalApiController extends AbstractController
{
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
     * External API-Endpoint
     *
     * This endpoint use the Aoe\Restler\System\RestApi\RestApiClient to call REST-API-Endpoints directly by using a
     * PHP-client (without 'leaving' the current PHP-process... this can save us a lot of performance).
     *
     * This can be useful if we want to call REST-API-Endpoints inside another REST-API-Endpoint
     * (to merge/use the result/data of several REST-API-Endpoints) or to call REST-API-Endpoints inside a
     * TYPO3-extBase-plugin.
     *
     * @url GET external_endpoint/cars/
     *
     * @return array[\Aoe\RestlerExamples\Domain\Model\Car] {@type array[\Aoe\RestlerExamples\Domain\Model\Car]}
     */
    public function getCars()
    {
        try {
            // 1. call REST-API
            $carId = 1;
            $car1 = $this->restApiClient->executeRequest('GET', '/api/motorsport/cars/'.$carId);

            // 2. call REST-API
            $carId = 2;
            $car2 = $this->restApiClient->executeRequest('GET', '/api/motorsport/cars/'.$carId);

            // 3. do reconstitution
            $cars = array($car1, $car2);

            return $cars;
        } catch (RestApiRequestException $e) {
            $this->throwRestException(self::HTTP_STATUS_CODE_BAD_REQUEST, 1446132825, $e->getMessage(), $e);
        }

        $cars = array();

        return $cars;
    }
}
