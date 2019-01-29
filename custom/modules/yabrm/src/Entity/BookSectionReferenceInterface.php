<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Book Section reference entities.
 *
 * @ingroup yabrm
 */
interface BookSectionReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

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
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setIsbn($isbn);

  /**
   * Gets the book Publication Title.
   *
   * @return string
   *   The Publication Title of the book.
   */
  public function getPublicationTitle();

  /**
   * Sets the Publication Title of the book.
   *
   * @param string $publication_title
   *   The Publication Title of the book.
   *
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setPublicationTitle($publication_title);

  /**
   * Gets the pages of the book section.
   *
   * @return string
   *   The pages of the book section.
   */
  public function getPages();

  /**
   * Sets the pages of the book reference.
   *
   * @param string $pages
   *   The pages of the book section.
   *
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setPages($pages);

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
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
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
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setNumberOfVolumes($num_volumes);

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
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setSeriesNumber($series_number);

  /**
   * Gets the publisher of the book.
   *
   * @return string
   *   The publisher of the book.
   */
  public function getPublisher();

  /**
   * Sets the publisher of the book.
   *
   * @param string $publisher
   *   The publisher of the book.
   *
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setPublisher($publisher);

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
   *   The publisher of the book.
   *
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
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
   * @return \Drupal\yabrm\Entity\BookSectionReferenceInterface
   *   The called Book Section Reference entity.
   */
  public function setEdition($edition);

}
