<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Reference entities.
 *
 * @ingroup yabrm
 */
interface BibliographicReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bibliographic Reference name.
   *
   * @return string
   *   Name of the Bibliographic Reference.
   */
  public function getName();

  /**
   * Sets the Bibliographic Reference name.
   *
   * @param string $name
   *   The Bibliographic Reference name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setName($name);

  /**
   * Gets the Bibliographic Reference creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Reference.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Reference creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Reference creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliographic Reference published status indicator.
   *
   * Unpublished Bibliographic Reference are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Reference is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Reference.
   *
   * @param bool $published
   *   TRUE to set this Bibliographic Reference to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Reference revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Reference revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Get the Bibliographic Reference revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Reference revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Gets the contributors associated with this bibliographic reference.
   *
   * @param string $role
   *   Only return contributors matching this role. Ignored if NULL.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributor[]
   *   The contributors.
   */
  public function getContributors($role);

  /**
   * Sets the Bibliographic contributors.
   *
   * @param \Drupal\paragraphs\Entity\Paragraph[] $contributors
   *   An array of contributor paragraphs entities.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setContributors(array $contributors);

  /**
   * Gets the sortable timestamp of the reference.
   *
   * @return int
   *   The UNIX epoch timestamp approximation for the reference date.
   */
  public function getSortTimestamp();

  /**
   * Gets the display date of the reference.
   *
   * @return string
   *   The constructed display date of the reference.
   */
  public function getDisplayDate();

  /**
   * Gets the publication year of the reference.
   *
   * @return int
   *   The publication year of the reference.
   */
  public function getPublicationYear();

  /**
   * Sets the publication year of the reference.
   *
   * @param int $year
   *   Integer publication year.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationYear(int $year);

  /**
   * Gets the numeric publication month of the reference.
   *
   * @return int
   *   The numeric publication month of the reference.
   */
  public function getPublicationMonth();

  /**
   * Sets the publication month of the reference.
   *
   * @param int $month
   *   Integer publication month.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationMonth(int $month);

  /**
   * Gets the numeric publication day of the reference.
   *
   * @return int
   *   The numeric publication day of the reference.
   */
  public function getPublicationDay();

  /**
   * Sets the publication day of the reference.
   *
   * @param int $day
   *   Integer publication day.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationDay(int $day);

  /**
   * Gets the Bibliographic Reference language.
   *
   * @return string
   *   Language of the Bibliographic Reference.
   */
  public function getLanguage();

  /**
   * Sets the Bibliographic Reference language.
   *
   * @param string $language
   *   The Bibliographic Reference language.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setlanguage($language);

}
