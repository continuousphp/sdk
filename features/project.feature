Feature: continuousphp Project
  As a Developer
  I need to be able to access my projects from continuousphp API
  
  Scenario: Get the project list
    Given I've instatiated the sdk with the following
    | token | cc2efee7-be03-4611-923e-065bc3dd3326 |
    When I call the "getProjects" operation
    Then The response should be a "project" collection

  Scenario: Get a specific project
    Given I've instatiated the sdk with the following
      | token | cc2efee7-be03-4611-923e-065bc3dd3326 |
    When I call the "getProject" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
    Then The response should be a "project" resource