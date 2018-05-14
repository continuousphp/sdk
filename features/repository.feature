Feature: continuousphp Project
  As a Developer
  I need to be able to access my projects from continuousphp API

  @debugguzzle6
  Scenario: Get the repository list
    Given I've instatiated the sdk with the following
    | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getRepositories" operation
    Then The response should be a "repository" collection