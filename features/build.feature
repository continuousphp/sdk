Feature: continuousphp build
  As a Developer
  I need to be able to access a build from continuousphp API

  Scenario: Get the build list of a specific project
    Given I've instatiated the sdk with the following
      | token | efdddd2b23324c695374e7f0a8e6f8bc9f572f40 |
    When I call the "getBuilds" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
    Then The response should be a "build" collection
    
  Scenario: Get a specific build
    Given I've instatiated the sdk with the following
      | token | efdddd2b23324c695374e7f0a8e6f8bc9f572f40 |
    When I call the "getBuild" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
      | buildId | a6b9301c-6aed-4921-9f59-a9fac2602148 |
    Then The response should be a "build" resource  
    
  Scenario: Get a download url for a specific build
    Given I've instatiated the sdk with the following
      | token | efdddd2b23324c695374e7f0a8e6f8bc9f572f40 |
    When I call the "getPackage" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
      | buildId | a6b9301c-6aed-4921-9f59-a9fac2602148 |
      | packageType | deploy |
    Then The response should contain a "url"  
    
  Scenario: Download a specific build package
    Given I've instatiated the sdk with the following
      | token | efdddd2b23324c695374e7f0a8e6f8bc9f572f40 |
    When I call the "downloadPackage" operation with
      | provider | git-hub |
      | repository | continuousphp/sdk |
      | buildId | a6b9301c-6aed-4921-9f59-a9fac2602148 |
      | packageType | deploy |
      | destination | /tmp/ |
    Then The response should contain a "path"
    And The "path" file should exists