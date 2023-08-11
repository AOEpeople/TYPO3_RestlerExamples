<?php

declare(strict_types=1);

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

use Aoe\RestlerExamples\Domain\Model\Car;
use Aoe\RestlerExamples\Domain\Model\Manufacturer;
use Aoe\Restler\System\RestApi\RestApiClient;
use Aoe\Restler\System\RestApi\RestApiRequestException;
use Luracast\Restler\RestException;
use Exception;
use stdClass;

/**
 * @IgnoreAnnotation("url")
 * @IgnoreAnnotation("status")
 */
class ExternalApiController
{
    public const HTTP_STATUS_CODE_BAD_REQUEST = 400;

    private RestApiClient $restApiClient;

    public function __construct(RestApiClient $restApiClient)
    {
        $this->restApiClient = $restApiClient;
    }

    /**
     * API-Endpoint, which call internal API-Endpoints via GET
     *
     * This endpoint use the Aoe\Restler\System\RestApi\RestApiClient to call REST-API-Endpoint directly by using a
     * PHP-client (without 'leaving' the current PHP-process... this can save us a lot of performance).
     *
     * This can be useful if we want to call REST-API-Endpoints inside another REST-API-Endpoint
     * (to merge/use the result/data of several REST-API-Endpoints) or to call REST-API-Endpoints inside a
     * TYPO3-extBase-plugin.
     *
     * @url GET external_endpoint/cars/
     *
     * @return array {@type \Aoe\RestlerExamples\Domain\Model\Car}
     */
    public function getListOfCars(): array
    {
        try {
            return [
                $this->convertDataToCarObject($this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/1')),
                $this->convertDataToCarObject($this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/2')),
                $this->convertDataToCarObject($this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/3')),
                $this->convertDataToCarObject($this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/4')),
                $this->convertDataToCarObject($this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/5')),
            ];
        } catch (RestApiRequestException $e) {
            $this->throwRestException(self::HTTP_STATUS_CODE_BAD_REQUEST, 1446132825, $e->getMessage(), $e);
        }
    }

    /**
     * API-Endpoint, which call internal API-Endpoint via GET
     *
     * This endpoint use the Aoe\Restler\System\RestApi\RestApiClient to call REST-API-Endpoint directly by using a
     * PHP-client (without 'leaving' the current PHP-process... this can save us a lot of performance).
     *
     * This can be useful if we want to call REST-API-Endpoints inside another REST-API-Endpoint
     * (to merge/use the result/data of several REST-API-Endpoints) or to call REST-API-Endpoints inside a
     * TYPO3-extBase-plugin.
     *
     * @url GET external_endpoint/cars/{id}
     *
     * @param integer $id
     * @return Car {@type \Aoe\RestlerExamples\Domain\Model\Car}
     */
    public function getCarById($id)
    {
        try {
            // 1. call REST-API - REST-API will return a stdClass-object, which contains the car-data
            $carData = $this->restApiClient->executeRequest('GET', '/api/rest-api-client/internal_endpoint/cars/' . $id);

            // 2. do reconstitution (create 'real' objects on base of the stdClass-object)
            return $this->convertDataToCarObject($carData);
        } catch (RestApiRequestException $e) {
            $this->throwRestException(self::HTTP_STATUS_CODE_BAD_REQUEST, 1446132825, $e->getMessage(), $e);
        }
    }

    /**
     * API-Endpoint, which call internal API-Endpoint via POST
     *
     * This endpoint use the Aoe\Restler\System\RestApi\RestApiClient to call REST-API-Endpoint directly by using a
     * PHP-client (without 'leaving' the current PHP-process... this can save us a lot of performance).
     *
     * This can be useful if we want to call REST-API-Endpoints inside another REST-API-Endpoint
     * (to merge/use the result/data of several REST-API-Endpoints) or to call REST-API-Endpoints inside a
     * TYPO3-extBase-plugin.
     *
     * @url POST external_endpoint/cars
     * @status 201
     */
    public function buyCar(Car $car): Car
    {
        try {
            // 1. call REST-API - REST-API will return a stdClass-object, which contains the car-data
            $getData = null;
            $postData = new stdClass();
            $postData->id = $car->id;
            $postData->models = $car->models;
            $postData->manufacturer = new stdClass();
            $postData->manufacturer->id = $car->manufacturer->id;
            $postData->manufacturer->name = $car->manufacturer->name;
            $carData = $this->restApiClient->executeRequest('POST', '/api/rest-api-client/internal_endpoint/cars', $getData, $postData);

            // 2. do reconstitution (create 'real' objects on base of the stdClass-object)
            return $this->convertDataToCarObject($carData);
        } catch (RestApiRequestException $e) {
            $this->throwRestException(self::HTTP_STATUS_CODE_BAD_REQUEST, 1446132826, $e->getMessage(), $e);
        }
    }

    private function throwRestException(int $httpStatusCode, int $errorCode, string $errorMessage, ?Exception $exception = null)
    {
        $details = ['error_code' => $errorCode, 'error_message' => $errorMessage];
        throw new RestException($httpStatusCode, null, $details, $exception);
    }

    private function convertDataToCarObject(stdClass $carData): Car
    {
        $car = new Car();
        $car->id = $carData->id;
        $car->models = $carData->models;
        $car->manufacturer = new Manufacturer();
        $car->manufacturer->id = $carData->manufacturer->id;
        $car->manufacturer->name = $carData->manufacturer->name;
        return $car;
    }
}
