<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Contributor entities.
 *
 * @ingroup yabrm
 */
interface BibliographicContributorInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Bibliographic Contributor first name.
   *
   * @return string
   *   First name of the Bibliographic Contributor.
   */
  public function getFirstName();

  /**
   * Sets the Bibliographic Contributor first name.
   *
   * @param string $first_name
   *   The Bibliographic Contributor first name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setFirstName($first_name);

  /**
   * Gets the Bibliographic Contributor last name.
   *
   * @return string
   *   Last name of the Bibliographic Contributor.
   */
  public function getLastName();

  /**
   * Sets the Bibliographic Contributor last name.
   *
   * @param string $last_name
   *   The Bibliographic Contributor last name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setLastName($last_name);

  /**
   * Gets the Bibliographic Contributor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Contributor.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Contributor creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Contributor creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliographic Contributor published status indicator.
   *
   * Unpublished Bibliographic Contributor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Contributor is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Contributor.
   *
   * @param bool $published
   *   TRUE to set this Bibliographic Contributor to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Contributor revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Contributor revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Bibliographic Contributor revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Contributor revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setRevisionUserId($uid);

}
