Feature: continuousphp Project
  As a Developer
  I need to be able to access my projects from continuousphp API
  
  Scenario: Get the repository list
    Given I've instatiated the sdk with the following
    | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getRepositories" operation
    Then The response should be a "repository" collection

  Scenario: Create a specific project
    Given I've instatiated the sdk with the following
      | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "createProject" operation with
      | provider    | git-hub                 |
      | url         | fdewinnetest/sdk        |
      | description | my project description  |
    Then a 409 error should occurs