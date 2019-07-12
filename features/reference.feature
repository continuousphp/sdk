Feature: continuousphp reference fetching
    As a developer
    I need to be able to access the references a project is build against

    Scenario: Get the reference list of a specific project
        Given I've instatiated the sdk with the following
          | token | b52f9c7faf680988f88391b35e5e488883442036 |
        When I call the "getReferences" operation with
          | provider   | git-hub           |
          | repository | continuousphp/sdk |
        Then the response should be a "reference" collection

