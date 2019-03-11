<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Book reference entities.
 *
 * @ingroup yabrm
 */
interface BookReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the book ISBN.
   *
   * @return string
   *   The ISBN of the book.
   */
  public function getIsbn();

  /**
   * Sets the ISBN of the book.
   *
   * @param string $isbn
   *   The ISBN of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setIsbn($isbn);

  /**
   * Gets the number of pages in the book.
   *
   * @return string
   *   The number of pages in the book.
   */
  public function getNumberOfPages();

  /**
   * Sets the number of pages in the book.
   *
   * @param string $num_pages
   *   The number of pages in the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setNumberOfPages($num_pages);

  /**
   * Gets the book Volume.
   *
   * @return string
   *   The volume of the book.
   */
  public function getVolume();

  /**
   * Sets the volume of the book.
   *
   * @param string $volume
   *   The volume of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setVolume($volume);

  /**
   * Gets the number of volumes in the book.
   *
   * @return string
   *   The number of volumes in the book.
   */
  public function getNumberOfVolumes();

  /**
   * Sets the number of volumes in the book.
   *
   * @param string $num_volumes
   *   The number of volumes in the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setNumberOfVolumes($num_volumes);

  /**
   * Gets the series of the book.
   *
   * @return string
   *   The series of the book.
   */
  public function getSeries();

  /**
   * Sets the series of the book.
   *
   * @param string $series
   *   The series of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setSeries($series);

  /**
   * Gets the series number of the book.
   *
   * @return string
   *   The series number of the book.
   */
  public function getSeriesNumber();

  /**
   * Sets the series number of the book.
   *
   * @param string $series_number
   *   The series number of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setSeriesNumber($series_number);

  /**
   * Gets the place of the book.
   *
   * @return string
   *   The place of the book.
   */
  public function getPlace();

  /**
   * Sets the place of the book.
   *
   * @param string $place
   *   The place of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setPlace($place);

  /**
   * Gets the edition of the book.
   *
   * @return string
   *   The edition of the book.
   */
  public function getEdition();

  /**
   * Sets the edition of the book.
   *
   * @param string $edition
   *   The edition of the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setEdition($edition);

}
