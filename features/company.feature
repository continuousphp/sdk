Feature: continuousphp Company
  As a Developer
  I need to be able to access my companies from continuousphp API

  Scenario: Get the company list
    Given I've instatiated the sdk with the following
    | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getCompanies" operation
    Then The response should be a "company" collection