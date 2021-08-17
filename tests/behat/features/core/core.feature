@api
Feature: Core
  In order to know the website is running
  As a website user
  I need to be able to view the site title and login

  @api
  Scenario: Visit as an anonymous user
    When I visit "/user"
    Then I should see "Log in"

  Scenario: Anonymous contributors cannot add data
    When I visit "/bibliography"
    Then I should not see a "a.custom-editor-menu" element
