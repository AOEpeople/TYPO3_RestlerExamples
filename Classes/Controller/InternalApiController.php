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

class InternalApiController extends AbstractController
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
     * Internal API-Endpoint
     *
     * This internal API-endpoint is protected from outside, if production-mode is active.
     * Use Aoe\Restler\System\RestApi\RestApiClient to call this endpoint.
     *
     * @url GET internal_endpoint/cars/
     *
     * @access protected
     * @class Aoe\RestlerExamples\Controller\InternalApiAuthenticationController {@checkAuthentication true}
     * @return array {@type \Aoe\RestlerExamples\Domain\Model\Car}
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
    }
}
