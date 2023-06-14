<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Thesis reference entities.
 *
 * @ingroup yabrm
 */
interface ThesisReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

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
   * Gets the type of thesis.
   *
   * @return string
   *   The type of thesis.
   */
  public function getThesisType();

  /**
   * Sets the type of thesis.
   *
   * @param string $thesis_type
   *   The type of thesis.
   *
   * @return \Drupal\yabrm\Entity\ThesisReferenceInterface
   *   The called Thesis Reference entity.
   */
  public function setThesisType($thesis_type);

}
