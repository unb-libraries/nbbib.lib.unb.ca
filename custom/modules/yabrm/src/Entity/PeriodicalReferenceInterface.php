<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Periodical reference entities.
 *
 * @ingroup yabrm
 */
interface PeriodicalReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the number of pages in the book.
   *
   * @return string
   *   The number of pages in the book.
   */
  public function getOrganization();

  /**
   * Sets the number of pages in the book.
   *
   * @param string $organization
   *   The number of pages in the book.
   *
   * @return \Drupal\yabrm\Entity\BookReferenceInterface
   *   The called Book Reference entity.
   */
  public function setOrganization($organization);

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
   * Gets the type of periodical.
   *
   * @return string
   *   The type of periodical.
   */
  public function getIssn();

  /**
   * Sets the type of periodical.
   *
   * @param string $issn
   *   The type of periodical.
   *
   * @return \Drupal\yabrm\Entity\PeriodicalReferenceInterface
   *   The called Periodical Reference entity.
   */
  public function setIssn($issn);

  /**
   * Gets the first publication year of the reference.
   *
   * @return int
   *   The publication year of the reference.
   */
  public function getFirstYear();

  /**
   * Sets the first publication year of the reference.
   *
   * @param int $first_year
   *   Integer first publication year.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setFirstYear($first_year);

}
