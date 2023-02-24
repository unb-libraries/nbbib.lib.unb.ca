<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contrib Archival entities.
 *
 * @ingroup yabrm
 */
interface ContribArchivalInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Contrib Archival name.
   *
   * @return string
   *   Name of the Contrib Archival.
   */
  public function getName();

  /**
   * Sets the Contrib Archival name.
   *
   * @param string $name
   *   The Contrib Archival name.
   *
   * @return \Drupal\yabrm\Entity\ContribArchivalInterface
   *   The called Contrib Archival entity.
   */
  public function setName($name);

  /**
   * Gets the Contrib Archival creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contrib Archival.
   */
  public function getCreatedTime();

  /**
   * Sets the Contrib Archival creation timestamp.
   *
   * @param int $timestamp
   *   The Contrib Archival creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\ContribArchivalInterface
   *   The called Contrib Archival entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Contrib Archival revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Contrib Archival revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\ContribArchivalInterface
   *   The called Contrib Archival entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Contrib Archival revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Contrib Archival revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\ContribArchivalInterface
   *   The called Contrib Archival entity.
   */
  public function setRevisionUserId($uid);

}
