<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2019 AOE GmbH <dev@aoe.com>
 *  All rights reserved
 *
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use Behat\Behat\Context\Context;
use JsonSchema\Validator;
use GuzzleHttp\Client as GuzzleRestClient;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;

/**
 * Rest context.
 */
class RestContext implements Context
{
    /**
     * @var GuzzleRestClient
     */
    protected $client;
    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $requestCookies = [];
    /**
     * @var array
     */
    protected $requestHeaders = [];
    /**
     * @var string
     */
    protected $requestMethod = 'get';
    /**
     * @var array|stdClass
     */
    protected $requestObject;
    /**
     * @var string
     */
    protected $requestObjectType = '';
    /**
     * @var string
     */
    protected $requestUrl = '';

    /**
     * @var ResponseInterface
     */
    private $response;
    /**
     * @var string
     */
    private $responseContentType = '';
    /**
     * @var mixed
     */
    private $responseData;
    /**
     * @var string
     */
    private $responseDataAsString = '';

    /**
     * Initializes context - Every scenario gets it's own context object.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->requestObject = new stdClass();
        $this->parameters = $parameters;

        $config = array(
            RequestOptions::HTTP_ERRORS => false, // we convert failed requests to our own exceptions
            RequestOptions::TIMEOUT  => 90,
            RequestOptions::VERIFY => false // use self-made SSL-certificates...so we can not verify them
        );
        $this->client = new GuzzleRestClient($config);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @param string $cookieName
     * @param string $cookieValue
     * @throws Exception
     */
    public function setCookie($cookieName, $cookieValue)
    {
        $this->printDebug('Set Cookie: ' . $cookieName . ' -> ' . $cookieValue);
        $this->requestCookies[$cookieName] = $cookieValue;
    }

    /**
     * Prints beautified debug string.
     *
     * @param string $string debug string
     */
    private function printDebug($string)
    {
        echo "\n\033[36m|  " . strtr($string, array("\n" => "\n|  ")) . "\033[0m\n\n";
    }

    /**
     * ============ json array ===================
     * @Given /^that I send (\[[^]]*\])$/
     *
     * ============ json object ==================
     * @Given /^that I send (\{(?>[^\{\}]+|(?1))*\})$/
     *
     * ============ json string ==================
     * @Given /^that I send ("[^"]*")$/
     *
     * ============ json int =====================
     * @Given /^that I send ([-+]?[0-9]*\.?[0-9]+)$/
     *
     * ============ json null or boolean =========
     * @Given /^that I send (null|true|false)$/
     */
    public function thatISend($data)
    {
        $this->requestObject = json_decode($data);
        $this->requestMethod = 'post';
    }

    /**
     * ============ json array ===================
     * @Given /^the response contains (\[[^]]*\])$/
     *
     * ============ json object ==================
     * @Given /^the response contains (\{(?>[^\{\}]+|(?1))*\})$/
     *
     * ============ json string ==================
     * @Given /^the response contains ("[^"]*")$/
     *
     * ============ json int =====================
     * @Given /^the response contains ([-+]?[0-9]*\.?[0-9]+)$/
     *
     * ============ json null or boolean =========
     * @Given /^the response contains (null|true|false)$/
     */
    public function theResponseContains($expectedResponse)
    {
        if (false === strpos($this->responseDataAsString, $expectedResponse)) {
            throw new Exception("Response value does not contain '$expectedResponse' only\n\n" . $this->echoLastResponse(), 1515049265);
        }
    }

    /**
     * @Then /^echo last response$/
     */
    private function echoLastResponse()
    {
        $requestCookies = '';
        foreach ($this->requestCookies as $cookieKey => $cookieValue) {
            if (false === empty($requestCookies)) {
                $requestCookies .= PHP_EOL;
            }
            $requestCookies .= $cookieKey . ' -> ' . $cookieValue;
        }
        $this->printDebug(
            'Request-URL: ' . $this->requestUrl . PHP_EOL .
            'Request-Cookies: ' . $requestCookies . PHP_EOL .
            'Response-Status: ' . $this->response->getStatusCode() . ': ' . $this->response->getReasonPhrase() . PHP_EOL .
            'Response-Data: ' . $this->responseDataAsString
        );
    }

    /**
     * ============ json array ===================
     * @Given /^the response equals (\[[^]]*\])$/
     *
     * ============ json object ==================
     * @Given /^the response equals (\{(?>[^\{\}]+|(?1))*\})$/
     *
     * ============ json string ==================
     * @Given /^the response equals ("[^"]*")$/
     *
     * ============ json int =====================
     * @Given /^the response equals ([-+]?[0-9]*\.?[0-9]+)$/
     *
     * ============ json null or boolean =========
     * @Given /^the response equals (null|true|false)$/
     */
    public function theResponseEquals($expectedResponse)
    {
        if ($this->responseDataAsString !== $expectedResponse) {
            throw new Exception("Response value does not match '$expectedResponse'\n\n" . $this->echoLastResponse(), 1515049266);
        }
    }

    /**
     * @Then /^the response Cache-Control header without numeric value check should be "([^"]*)"$/
     * @param string $value
     * @throws Exception
     */
    public function theResponseHeaderWithoutNumericValuesCheckShouldBe($value)
    {
        if (!$this->response->hasHeader('Cache-Control')) {
            throw new Exception("Response header Cache-Control was not found\n\n" . $this->echoLastResponse());
        }

        $headerWithoutNumbers = preg_replace('!\d+!', '', implode('',$this->response->getHeader('Cache-Control')));
        $valueWithoutNumbers = preg_replace('!\d+!', '', (string)$value);

        if ($headerWithoutNumbers != $valueWithoutNumbers) {
            throw new Exception(
                "Response header Cache-Control (". $this->response->getHeader('Cache-Control')
                .") does not match `$valueWithoutNumbers`\n\n"
                . $this->echoLastResponse()
            );
        }
    }

    /**
     * @Given /^that I want to make a new "([^"]*)"$/
     *
     * @param string $objectType
     */
    public function thatIWantToMakeANew($objectType)
    {
        $this->requestObjectType = ucwords(strtolower($objectType));
        $this->requestMethod = 'post';
    }

    /**
     * @Given /^that I want to update "([^"]*)"$/
     * @Given /^that I want to update an "([^"]*)"$/
     * @Given /^that I want to update a "([^"]*)"$/
     *
     * @param string $objectType
     */
    public function thatIWantToUpdate($objectType)
    {
        $this->requestObjectType = ucwords(strtolower($objectType));
        $this->requestMethod = 'put';
    }

    /**
     * @Given /^that its "([^"]*)" is "([^"]*)"$/
     * @Given /^that his "([^"]*)" is "([^"]*)"$/
     * @Given /^that her "([^"]*)" is "([^"]*)"$/
     * @Given /^its "([^"]*)" is "([^"]*)"$/
     * @Given /^his "([^"]*)" is "([^"]*)"$/
     * @Given /^her "([^"]*)" is "([^"]*)"$/
     * @Given /^that "([^"]*)" is set to "([^"]*)"$/
     * @Given /^"([^"]*)" is set to "([^"]*)"$/
     *
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function thatItsStringPropertyIs($propertyName, $propertyValue)
    {
        $this->requestObject->$propertyName = $propertyValue;
    }

    /**
     * @Given /^that "([^"]*)" collection is set to "([^"]*(,[^"]*)*)"$/
     * @Given /^"([^"]*)" collection is set to "([^"]*(,[^"]*)*)"$/
     *
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function thatItsStringArrayPropertyIs($propertyName, $propertyValue)
    {
        $strArray = explode(',', $propertyValue);
        $this->requestObject->$propertyName = $strArray;
    }

    /**
     * @Given /^that "([^"]*)" collection is set to (\d+(,\d+)*)$/
     * @Given /^"([^"]*)" collection is set to (\d+(,\d+)*)$/
     *
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function thatItsNumericArrayPropertyIs($propertyName, $propertyValue)
    {
        $intArray = array_map('intval', explode(',', $propertyValue));
        $this->requestObject->$propertyName = $intArray;
    }

    /**
     * @Given /^that its "([^"]*)" is (\d+)$/
     * @Given /^that his "([^"]*)" is (\d+)$/
     * @Given /^that her "([^"]*)" is (\d+)$/
     * @Given /^its "([^"]*)" is (\d+)$/
     * @Given /^his "([^"]*)" is (\d+)$/
     * @Given /^her "([^"]*)" is (\d+)$/
     * @Given /^that "([^"]*)" is set to (\d+)$/
     * @Given /^"([^"]*)" is set to (\d+)$/
     *
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function thatItsNumericPropertyIs($propertyName, $propertyValue)
    {
        if (is_float($propertyValue)) {
            $this->requestObject->$propertyName = floatval($propertyValue);
        } else {
            $this->requestObject->$propertyName = intval($propertyValue);
        }
    }

    /**
     * @Given /^that its "([^"]*)" is (true|false)$/
     * @Given /^that his "([^"]*)" is (true|false)$/
     * @Given /^that her "([^"]*)" is (true|false)$/
     * @Given /^its "([^"]*)" is (true|false)$/
     * @Given /^his "([^"]*)" is (true|false)$/
     * @Given /^her "([^"]*)" is (true|false)$/
     * @Given /^that "([^"]*)" is set to (true|false)$/
     * @Given /^"([^"]*)" is set to (true|false)$/
     *
     * @param string $propertyName
     * @param string $propertyValue
     */
    public function thatItsBooleanPropertyIs($propertyName, $propertyValue)
    {
        $this->requestObject->$propertyName = ($propertyValue === 'true');
    }

    /**
     * @Given /^the request is sent as JSON$/
     * @Given /^the request is sent as Json$/
     */
    public function theRequestIsSentAsJson()
    {
        $this->requestHeaders['Content-Type'] = 'application/json; charset=utf-8';
    }

    /**
     * @When /^I request "([^"]*)" over HTTPS$/
     *
     * @param string $pageUrl
     */
    public function iRequestOverHttps($pageUrl)
    {
        $this->iRequest($pageUrl, true);
    }

    /**
     * @When /^I request "([^"]*)"$/
     * @When /^I request "([^"]*)" over HTTP$/
     *
     * @param string $pageUrl
     * @param boolean $isHttpsRequest
     * @throws Exception
     */
    public function iRequest($pageUrl)
    {
        $url = $this->parameters['base_url'] . $pageUrl;
        $urlParams = [];

        if (strpos($pageUrl, '{') !== false) {
            foreach ((array)$this->requestObject as $key => $value) {
                if (strpos($pageUrl, '{'.$key.'}') === false) {
                    $urlParams[$key] = $value;
                } else {
                    $url = str_replace('{' . $key . '}', $value, $url);
                }
            }
        }

        $options = [];
        $options[RequestOptions::ON_STATS] = function (TransferStats $stats) {
            $this->requestUrl = $stats->getEffectiveUri();
        };
        if (false === empty($this->requestCookies)) {
            $options[RequestOptions::COOKIES] = CookieJar::fromArray($this->requestCookies, $this->getParameter('base_url'));
        }
        if (false === empty($this->requestHeaders)) {
            $options[RequestOptions::HEADERS] = $this->requestHeaders;
        }
        if (false === empty($urlParams)) {
            $options[RequestOptions::QUERY] = $urlParams;
        }

        $requestMethod = strtoupper($this->requestMethod);
        if ($requestMethod === 'POST' || $requestMethod === 'PUT' || $requestMethod === 'PATCH') {
            if (is_object($this->requestObject)) {
                $options[RequestOptions::BODY] = json_encode((array)$this->requestObject);
            } else {
                $options[RequestOptions::BODY] = json_encode($this->requestObject);
            }
        }

        switch ($requestMethod) {
            case 'GET':
                $this->response = $this->client->get($url, $options);
                break;
            case 'POST':
                $this->response = $this->client->post($url, $options);
                break;
            case 'PUT':
                $this->response = $this->client->put($url, $options);
                break;
            case 'PATCH':
                $this->response = $this->client->patch($url, $options);
                break;
            case 'DELETE':
                $this->response = $this->client->delete($url, $options);
                break;
        }

        $contentType = $this->getHeaderByName('Content-type');
        if (false === strpos($contentType, 'application/json') && false === strpos($contentType, 'application/hal+json')) {
            throw new Exception ('Response is no JSON', 1515047979);
        }

        $this->responseContentType = 'json';
        $this->responseDataAsString = $this->response->getBody()->getContents();
        $this->responseData = json_decode($this->responseDataAsString);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'REST-call was not successfull - json_last_error was:' . json_last_error() . ' - ' . json_last_error_msg() . '!';
            throw new Exception($message, 1515047980);
        }
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws Exception
     */
    public function getParameter($name)
    {
        if (count($this->parameters) === 0) {
            throw new \Exception('Parameters not loaded!', 1515049264);
        }

        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }
        return null;
    }

    /**
     * @param string $headerName
     * @return string
     */
    private function getHeaderByName($headerName)
    {
        return implode('', $this->response->getHeader($headerName));
    }

    /**
     * @Then /^the response is JSON$/
     * @Then /^the response should be JSON$/
     */
    public function theResponseIsJson()
    {
        if ($this->responseContentType != 'json') {
            throw new Exception("Response was not JSON\n\n" . $this->echoLastResponse(), 1515049267);
        }
    }

    /**
     * @Then /^the response "([^"]*)" header should be "([^"]*)"$/
     * @param string $headerName
     * @param string $expectedHeaderValue
     * @throws Exception
     */
    public function theResponseHeaderShouldBe($headerName, $expectedHeaderValue)
    {
        if (!$this->response->hasHeader($headerName)) {
            throw new Exception("Response header $headerName was not found\n\n" . $this->echoLastResponse(), 1515049268);
        }
        $currentHeaderValue = $this->getHeaderByName($headerName);
        if ($currentHeaderValue !== $expectedHeaderValue) {
            $message = sprintf(
                "Response header '%s' (with value '%s') does not match '%s'\n\n%s",
                $headerName,
                $currentHeaderValue,
                $expectedHeaderValue,
                $this->echoLastResponse()
            );
            throw new Exception($message, 1514987384);
        }
    }

    /**
     * @Then /^the response is JSON "([^"]*)"$/
     *
     * @param string $type
     * @throws Exception
     */
    public function theResponseIsJsonWithType($type)
    {
        if ($this->responseContentType != 'json') {
            throw new Exception("Response was not JSON\n\n" . $this->echoLastResponse(), 1515049269);
        }

        if (($type === 'array' && false === is_array($this->responseData)) ||
            ($type === 'float' && false === is_float($this->responseData)) ||
            ($type === 'int' && false === is_int($this->responseData)) ||
            ($type === 'object' && false === is_object($this->responseData)) ||
            ($type === 'string' && false === is_string($this->responseData)) ||
            ($type === 'null' && false === is_null($this->responseData))) {
            throw new Exception("Response was JSON\n but not of type '$type'\n\n" . $this->echoLastResponse(), 1515049270);
        }
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     *
     * @param integer $httpStatus
     * @throws Exception
     */
    public function theResponseStatusCodeShouldBe($httpStatus)
    {
        if ((string)$this->response->getStatusCode() !== $httpStatus) {
            throw new Exception(
                'HTTP code does not match ' . $httpStatus .
                ' (actual: ' . $this->response->getStatusCode() . ")\n\n"
                . $this->echoLastResponse(),
                1515049271
            );
        }
    }

    /**
     * @Given /^the response should match JSON schema "([^"]*)"$/
     *
     * @param string $jsonSchemaFile
     * @throws Exception
     */
    public function theResponseShouldMatchJsonSchema($jsonSchemaFile)
    {
        $jsonSchemaFile = sprintf('file://%s/../JSON-Schema/%s', __DIR__, $jsonSchemaFile);
        $validator = new Validator();
        $validator->validate($this->responseData, (object)['$ref' => $jsonSchemaFile]);

        if (false === $validator->isValid()) {
            $errorMsg = '';
            foreach ($validator->getErrors() as $error) {
                $errorMsg .= sprintf('Property "%s" is not valid: %s', $error['property'], $error['message']) . PHP_EOL;
            }
            throw new Exception($errorMsg, 1515049263);
        }
    }

    /**
     * @Given /^the response should contain error code (\d+)$/
     * @Given /^the response should contain error code $/
     *
     * @param string $errorCode
     * @throws \Exception Error code was not found in response
     */
    public function theResponseShouldContainErrorCode($errorCode = null)
    {
        // Check if we have an error code - If the error code is not set, this method is called in a valid test case
        if ($errorCode === null) {
            return;
        }

        // Search for the error code in the response body
        if (false === strpos($this->responseDataAsString, $errorCode)) {
            $errorMsg = sprintf('The error code "%s" was not found in the response.', $errorCode);
            throw new \Exception($errorMsg, 1514989806);
        }
    }
}
