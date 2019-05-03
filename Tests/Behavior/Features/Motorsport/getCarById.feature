@motorsport
Feature: Testing Car Example

  Scenario: Retrieving card by id
    Given that "carId" is set to "5"
    When I request "motorsport/cars/{carId}"
    Then the response status code should be 200
    And the response is JSON

  Scenario: Providing an invalid car id
    Given that "carId" is set to "X"
    When I request "motorsport/cars/{carId}"
    Then the response status code should be 400
    And the response is JSON
