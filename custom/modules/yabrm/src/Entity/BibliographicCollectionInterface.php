<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Collection entities.
 *
 * @ingroup yabrm
 */
interface BibliographicCollectionInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Bibliographic Collection name.
   *
   * @return string
   *   Name of the Bibliographic Collection.
   */
  public function getName();

  /**
   * Sets the Bibliographic Collection name.
   *
   * @param string $name
   *   The Bibliographic Collection name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setName($name);

  /**
   * Gets the Bibliographic Collection description.
   *
   * @return string
   *   Description of the Bibliographic Collection.
   */
  public function getDescription();

  /**
   * Sets the Bibliographic Collection description.
   *
   * @param string $description
   *   The Bibliographic Collection description.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setDescription($description);

  /**
   * Gets the Bibliographic Collection description.
   *
   * @return string
   *   Description of the Bibliographic Collection.
   */
  public function getDescriptionV2();

  /**
   * Sets the Bibliographic Collection description.
   *
   * @param string $description
   *   The Bibliographic Collection description.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setDescriptionV2($description);

  /**
   * Gets the Bibliographic Collection creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Collection.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Collection creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Collection creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliographic Collection published status indicator.
   *
   * Unpublished Bibliographic Collection are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Collection is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Collection.
   *
   * @param bool $published
   *   TRUE to set this Bibliographic Collection to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Collection revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Collection revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Bibliographic Collection revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Collection revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The called Bibliographic Collection entity.
   */
  public function setRevisionUserId($uid);

}
