@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
  Scenario: Visit as an anonymous user
    When I visit "/user"
    Then I should see the link "Log in"

  Scenario: Log in as a user created during this scenario
    Given users:
      | name      | status |
      | Test user |      1 |
    When I am logged in as "Test user"
    And I visit "/user"
    Then I should see "View"
    And I should not see the link "Log in"

  Scenario: Logged in contributors can add data
    Given I am logged in as a user with the "nb_bibliography_contributor" role
    When I visit "/bibliography"
    Then I should see the link "New Brunswick Bibliography"

  Scenario: Logged in contributors can add references and contributors
    Given I am logged in as a user with the "nb_bibliography_contributor" role
    When I visit "/bibliography"
    And I click "New Brunswick Bibliography"
    Then I should see "Content"
    And I should see "Add New Book"

  Scenario: Anonymous contributors cannot add data
    When I visit "/bibliography"
    Then I should not see the link "New Brunswick Bibliography"
