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
   * Gets the place of the conference.
   *
   * @return string
   *   The place of the conference.
   */
  public function getPlace();

  /**
   * Sets the place of the conference.
   *
   * @param string $place
   *   The place of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setPlace($place);

  /**
   * Gets the name of the conference.
   *
   * @return string
   *   The name of the conference.
   */
  public function getConferenceName();

  /**
   * Sets the name of the conference.
   *
   * @param string $conference_name
   *   The name of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setConferenceName($conference_name);

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
