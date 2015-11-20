Feature: continuousphp Company
  As a Developer
  I need to be able to access my companies from continuousphp API
  
  Scenario: Get the company list
    Given I've instatiated the sdk with the following
    | token | e391f57ddd27bb37097a5c46a47776289cf1eff7 |
    When I call the "getCompanies" operation
    Then The response should be a "company" collection