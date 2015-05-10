Feature: continuousphp Project
  As a Developer
  I need to be able to access my projects from continuousphp API
  
  Scenario: Get the last build of master branch
    Given I've instatiated the sdk with the following
    | token | cc2efee7-be03-4611-923e-065bc3dd3326 |
    When I call the "getProjects" operation
    Then The response should be a "project" collection