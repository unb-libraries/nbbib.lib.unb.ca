<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Film reference entities.
 *
 * @ingroup yabrm
 */
interface FilmReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the film distributor.
   *
   * @return string
   *   The distributor of the film.
   */
  public function getDistributor();

  /**
   * Sets the distributor of the film.
   *
   * @param string $distributor
   *   The distributor of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setDistributor($distributor);

  /**
   * Gets the date of original release for the film.
   *
   * @return string
   *   The date of original release for the film.
   */
  public function getReleased();

  /**
   * Sets the date of original release for the film.
   *
   * @param string $released
   *   The date of original release for the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setReleased($released);

  /**
   * Gets the genre of the film.
   *
   * @return string
   *   The genre of the film.
   */
  public function getGenre();

  /**
   * Sets the genre of the film.
   *
   * @param string $genre
   *   The genre of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setGenre($genre);

  /**
   * Gets the format of the film.
   *
   * @return string
   *   The format of the film.
   */
  public function getFilmFormat();

  /**
   * Sets the format of the film.
   *
   * @param string $film_format
   *   The format of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setFilmFormat($film_format);

  /**
   * Gets the running time of the film.
   *
   * @return string
   *   The running time of the film.
   */
  public function getRunningTime();

  /**
   * Sets the running time of the film.
   *
   * @param string $running_time
   *   The running time of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setRunningTime($running_time);

  /**
   * Gets the performers of the film.
   *
   * @return string
   *   The performers of the film.
   */
  public function getPerformers();

  /**
   * Sets the performers of the film.
   *
   * @param string $performers
   *   The performers of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setPerformers($performers);

  /**
   * Gets the place of the film.
   *
   * @return string
   *   The place of the film.
   */
  public function getPlace();

  /**
   * Sets the place of the film.
   *
   * @param string $place
   *   The place of the film.
   *
   * @return \Drupal\yabrm\Entity\FilmReferenceInterface
   *   The called Film Reference entity.
   */
  public function setPlace($place);

}
