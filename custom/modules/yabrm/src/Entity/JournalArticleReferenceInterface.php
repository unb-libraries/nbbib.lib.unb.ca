<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Journal Article Reference entities.
 *
 * @ingroup yabrm
 */
interface JournalArticleReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Journal Article Reference name.
   *
   * @return string
   *   Name of the Journal Article Reference.
   */
  public function getName();

  /**
   * Sets the Journal Article Reference name.
   *
   * @param string $name
   *   The Journal Article Reference name.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setName($name);

  /**
   * Gets the Journal Article Reference creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Journal Article Reference.
   */
  public function getCreatedTime();

  /**
   * Sets the Journal Article Reference creation timestamp.
   *
   * @param int $timestamp
   *   The Journal Article Reference creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Journal Article Reference published status indicator.
   *
   * Unpublished Journal Article Reference are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Journal Article Reference is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Journal Article Reference.
   *
   * @param bool $published
   *   TRUE to set this Journal Article Reference to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setPublished($published);

  /**
   * Gets the Journal Article Reference revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Journal Article Reference revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Journal Article Reference revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Journal Article Reference revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setRevisionUserId($uid);

}
