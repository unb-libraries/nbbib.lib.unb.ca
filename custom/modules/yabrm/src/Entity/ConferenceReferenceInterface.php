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
   * Gets the conference Volume.
   *
   * @return string
   *   The volume of the conference.
   */
  public function getVolume();

  /**
   * Sets the volume of the conference.
   *
   * @param string $volume
   *   The volume of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setVolume($volume);

  /**
   * Gets the number of volumes in the conference.
   *
   * @return string
   *   The number of volumes in the conference.
   */
  public function getNumberOfVolumes();

  /**
   * Sets the number of volumes in the conference.
   *
   * @param string $num_volumes
   *   The number of volumes in the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setNumberOfVolumes($num_volumes);

  /**
   * Gets the series of the conference.
   *
   * @return string
   *   The series of the conference.
   */
  public function getSeries();

  /**
   * Sets the series of the conference.
   *
   * @param string $series
   *   The series of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setSeries($series);

  /**
   * Gets the series number of the conference.
   *
   * @return string
   *   The series number of the conference.
   */
  public function getSeriesNumber();

  /**
   * Sets the series number of the conference.
   *
   * @param string $series_number
   *   The series number of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setSeriesNumber($series_number);

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
   * Gets the edition of the conference.
   *
   * @return string
   *   The edition of the conference.
   */
  public function getEdition();

  /**
   * Sets the edition of the conference.
   *
   * @param string $edition
   *   The edition of the conference.
   *
   * @return \Drupal\yabrm\Entity\ConferenceReferenceInterface
   *   The called Conference Reference entity.
   */
  public function setEdition($edition);

}
