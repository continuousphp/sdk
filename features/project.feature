Feature: continuousphp Project
  As a Developer
  I need to be able to access my projects from continuousphp API
  
  Scenario: Get the project list
    Given I've instatiated the sdk with the following
    | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getProjects" operation
    Then The response should be a "project" collection

  Scenario: Get a specific project
    Given I've instatiated the sdk with the following
      | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getProject" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
    Then The response should be a "project" resource
    
  Scenario: Get settings of a specific project
    Given I've instatiated the sdk with the following
      | token | b52f9c7faf680988f88391b35e5e488883442036 |
    When I call the "getPipelines" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
    Then The response should be a "setting" collection