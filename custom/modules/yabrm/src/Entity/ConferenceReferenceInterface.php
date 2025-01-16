<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Conference reference entities.
 *
 * @ingroup yabrm
 */
interface ConferenceReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the conference procedure ISBN.
   *
   * @return string
   *   The ISBN of the conference.
   */
  public function getIsbn();

  /**
   * Sets the ISBN of the conference.
   *
   * @param string $isbn
   *   The ISBN of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setIsbn($isbn);

  /**
   * Gets the number of pages in the conference.
   *
   * @return string
   *   The number of pages in the conference.
   */
  public function getNumberOfPages();

  /**
   * Sets the number of pages in the conference.
   *
   * @param string $num_pages
   *   The number of pages in the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setNumberOfPages($num_pages);

  /**
   * Gets the publication place of the conference.
   *
   * @return string
   *   The publication place of the conference.
   */
  public function getPlace();

  /**
   * Sets the publication place of the conference.
   *
   * @param string $place
   *   The publication place of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setPlace($place);

  /**
   * Gets the place of the conference.
   *
   * @return string
   *   The place of the conference.
   */
  public function getConferencePlace();

  /**
   * Sets the place of the conference.
   *
   * @param string $conference_place
   *   The place of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setConferencePlace($conference_place);

  /**
   * Gets the name of the conference.
   *
   * @return string
   *   The name of the conference.
   */
  public function getName();

  /**
   * Sets the name of the conference.
   *
   * @param string $name
   *   The name of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setName($name);

  /**
   * Gets the format of the conference.
   *
   * @return string
   *   The format of the conference.
   */
  public function getFormat();

  /**
   * Sets the format of the conference.
   *
   * @param string $format
   *   The format of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setFormat($format);

}
