<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Contributor Archival entities.
 *
 * @ingroup yabrm
 */
interface BibliographicContributorArchivalInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bibliographic Contributor Archival title.
   *
   * @return string
   *   Title of the Bibliographic Contributor Archival.
   */
  public function getTitle();

  /**
   * Sets the Bibliographic Contributor Archival title.
   *
   * @param string $title
   *   The Bibliographic Contributor Archival title.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setTitle($title);

  /**
   * Gets the Bibliographic Contributor Archival website/catalogue.
   *
   * @return string
   *   Website/catalogue of the Bibliographic Contributor Archival.
   */
  public function getWebsiteCatalogue();

  /**
   * Sets the Bibliographic Contributor Archival website/catalogue.
   *
   * @param string $website_catalogue
   *   The Bibliographic Contributor Archival website/catalogue.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setWebsiteCatalogue($website_catalogue);

  /**
   * Gets the Bibliographic Contributor Archival institution.
   *
   * @return string
   *   Institution of the Bibliographic Contributor Archival.
   */
  public function getInstitution();

  /**
   * Sets the Bibliographic Contributor Archival institution.
   *
   * @param string $institution
   *   The Bibliographic Contributor Archival institution.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setInstitution($institution);

  /**
   * Gets the Bibliographic Contributor Archival retrieval number.
   *
   * @return string
   *   Retrieval number of the Bibliographic Contributor Archival.
   */
  public function getRetrievalNumber();

  /**
   * Sets the Bibliographic Contributor Archival retrieval number.
   *
   * @param string $retrieval_number
   *   The Bibliographic Contributor Archival retrieval number.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setRetrievalNumber($retrieval_number);

  /**
   * Gets the Bibliographic Contributor Archival date range.
   *
   * @return string
   *   Date range of the Bibliographic Contributor Archival.
   */
  public function getDateRange();

  /**
   * Sets the Bibliographic Contributor Archival date range.
   *
   * @param string $date_range
   *   The Bibliographic Contributor Archival date range.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setDateRange($date_range);

  /**
   * Gets the Bibliographic Contributor Archival extent.
   *
   * @return string
   *   Extent of the Bibliographic Contributor Archival.
   */
  public function getExtent();

  /**
   * Sets the Bibliographic Contributor Archival extent.
   *
   * @param string $extent
   *   The Bibliographic Contributor Archival extent.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setExtent($extent);

  /**
   * Gets the Bibliographic Contributor Archival scope/content.
   *
   * @return string
   *   Scope/content of the Bibliographic Contributor Archival.
   */
  public function getScopeContent();

  /**
   * Sets the Bibliographic Contributor Archival scope/content.
   *
   * @param string $scope_content
   *   The Bibliographic Contributor Archival scope/content.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setScopeContent($scope_content);

  /**
   * Gets the Bibliographic Contributor Archival creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Contributor.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Contributor Archival creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Contributor Archival creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliographic Contributor Archival published status indicator.
   *
   * Unpublished Bibliographic Contributor Archival are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Contributor Archival is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Contributor.
   *
   * @param bool $published
   *   TRUE to set this Bibliographic Contributor Archival to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Contributor Archival revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Contributor Archival revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Bibliographic Contributor Archival revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Contributor Archival revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor Archival entity.
   */
  public function setRevisionUserId($uid);

}
