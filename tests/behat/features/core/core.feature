@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
  Scenario: Visit as an anonymous user
    When I visit "/"
    Then I should see the link "Log in"
    And I should not see the link "Log out"

  Scenario: Log in as a user created during this scenario
    Given users:
      | name      | status |
      | Test user |      1 |
    When I am logged in as "Test user"
    Then I should see the link "Log out"

  Scenario: Logged in contributors can add data
    Given I am logged in as a user with the "nb_bibliography_contributor" role
    When I visit "/"
    Then I should see the link "Add New"

  Scenario: Logged in contributors can add references and contributors
    Given I am logged in as a user with the "nb_bibliography_contributor" role
    When I visit "/"
    And I click "Add New"
    Then I should see "Contributor"
    And I should see "Journal Article"

  Scenario: Anonymous contributors cannot add data
    When I visit "/"
    Then I should not see the link "Add New"
