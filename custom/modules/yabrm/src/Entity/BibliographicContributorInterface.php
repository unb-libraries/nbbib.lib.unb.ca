<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Contributor entities.
 *
 * @ingroup yabrm
 */
interface BibliographicContributorInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bibliographic Contributor name.
   *
   * @return string
   *   Name of the Bibliographic Contributor.
   */
  public function getName();

  /**
   * Sets the Bibliographic Contributor name.
   *
   * @param string $name
   *   The Bibliographic Contributor name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setName($name);

  /**
   * Gets the Bibliographic Contributor first name.
   *
   * @return string
   *   First name of the Bibliographic Contributor.
   */
  public function getFirstName();

  /**
   * Gets the Bibliographic Contributor last name.
   *
   * @return string
   *   Last name of the Bibliographic Contributor.
   */
  public function getLastName();

  /**
   * Sets the Bibliographic Contributor last name.
   *
   * @param string $last_name
   *   The Bibliographic Contributor last name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setLastName($last_name);

  /**
   * Gets the Bibliographic Contributor institution name.
   *
   * @return string
   *   Institution name of the Bibliographic Contributor.
   */
  public function getInstitutionName();

  /**
   * Sets the Bibliographic Contributor institution name.
   *
   * @param string $institution_name
   *   The Bibliographic Contributor institution name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setInstitutionName($institution_name);

  /**
   * Gets the Bibliographic Contributor sort name.
   *
   * @return string
   *   Sort name of the Bibliographic Contributor.
   */
  public function getSortName();

  /**
   * Sets the Bibliographic Contributor sort name.
   *
   * @param string $sort_name
   *   The Bibliographic Contributor sort name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setSortName($sort_name);

  /**
   * Gets the Bibliographic Contributor prefix.
   *
   * @return string
   *   Prefix of the Bibliographic Contributor.
   */
  public function getPrefix();

  /**
   * Sets the Bibliographic Contributor prefix.
   *
   * @param string $prefix
   *   The Bibliographic Contributor prefix.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPrefix($prefix);

  /**
   * Gets the Bibliographic Contributor suffix.
   *
   * @return string
   *   Suffix of the Bibliographic Contributor.
   */
  public function getSuffix();

  /**
   * Sets the Bibliographic Contributor suffix.
   *
   * @param string $suffix
   *   The Bibliographic Contributor suffix.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setSuffix($suffix);

  /**
   * Gets the Bibliographic Contributor description.
   *
   * @return string
   *   Description of the Bibliographic Contributor.
   */
  public function getDescription();

  /**
   * Sets the Bibliographic Contributor description.
   *
   * @param string $description
   *   The Bibliographic Contributor description.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setDescription($description);

  /**
   * Gets the Bibliographic Contributor source.
   *
   * @return string
   *   Source of the Bibliographic Contributor.
   */
  public function getSource();

  /**
   * Sets the Bibliographic Contributor source.
   *
   * @param string $source
   *   The Bibliographic Contributor source.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setSource($source);

  /**
   * Gets the Bibliographic Contributor NBLE URL.
   *
   * @return string
   *   NBLE URL of the Bibliographic Contributor.
   */
  public function getNbleUrl();

  /**
   * Sets the Bibliographic Contributor NBLE URL.
   *
   * @param string $nble_url
   *   The Bibliographic Contributor NBLE URL.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setNbleUrl($nble_url);

  /**
   * Gets the Bibliographic Contributor Archival(s).
   *
   * @return \\Drupal\yabrm\BibliographicContributorInterface[]
   *   The Archival(s) of the Bibliographic Contributor.
   */
  public function getArchival();

  /**
   * Sets the Bibliographic Contributor Archival(s).
   *
   * @param \Drupal\yabrm\BibliographicContributorInterface[] $archival
   *   The Bibliographic Contributor archival(s).
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setArchival(array $archival);

  /**
   * Gets the Bibliographic Contributor creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Contributor.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Contributor creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Contributor creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bibliographic Contributor published status indicator.
   *
   * Unpublished Bibliographic Contributor are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Contributor is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Contributor.
   *
   * @param bool $published
   *   TRUE to set this Bibliographic Contributor to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Contributor revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Contributor revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Bibliographic Contributor revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Contributor revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Gets the Bibliographic Contributor picture.
   *
   * @return array
   *   The 'values' array for a Drupal\image\Plugin\Field\FieldType\ImageItem.
   */
  public function getPicture();

  /**
   * Sets the Bibliographic Contributor picture.
   *
   * @param mixed[] $values
   *   The 'values' array for a Drupal\image\Plugin\Field\FieldType\ImageItem.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPicture(array $values);

  /**
   * Gets the Bibliographic Contributor picture caption.
   *
   * @return string
   *   Picture caption of the Bibliographic Contributor.
   */
  public function getPicCaption();

  /**
   * Sets the Bibliographic Contributor picture caption.
   *
   * @param string $pic_caption
   *   The Bibliographic Contributor picture caption.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPicCaption($pic_caption);

  /**
   * Gets the Bibliographic Contributor picture credit.
   *
   * @return string
   *   Picture credit of the Bibliographic Contributor.
   */
  public function getPicCredit();

  /**
   * Sets the Bibliographic Contributor picture credit.
   *
   * @param string $pic_credit
   *   The Bibliographic Contributor picture credit.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setPicCredit($pic_credit);

  /**
   * Gets the birth year of the contributor.
   *
   * @return int
   *   The birth year of the contributor..
   */
  public function getBirthYear();

  /**
   * Sets the birth year of the contributor.
   *
   * @param int $year
   *   Integer birth year.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setBirthYear($year);

  /**
   * Gets the death year of the contributor.
   *
   * @return int
   *   The death year of the contributor..
   */
  public function getDeathYear();

  /**
   * Sets the death year of the contributor.
   *
   * @param int $year
   *   Integer death year.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setDeathYear($year);

  /**
   * Gets the Bibliographic Contributor NB residences.
   *
   * @return \Drupal\taxonomy\TermInterface[]
   *   The residence(s) of the Bibliographic Reference.
   */
  public function getNbResidences();

  /**
   * Sets the Bibliographic Contributor NB residences.
   *
   * @param \Drupal\taxonomy\TermInterface[] $nb_residences
   *   The Bibliographic Contributor residence(s).
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setNbResidences(array $nb_residences);

  /**
   * Gets the Bibliographic Contributor Archival Note.
   *
   * @return string
   *   Archival note of the Bibliographic Contributor.
   */
  public function getArchivalNote();

  /**
   * Sets the Bibliographic Contributor Archival Note.
   *
   * @param string $archival_note
   *   The Bibliographic Contributor Archival Note.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributorInterface
   *   The called Bibliographic Contributor entity.
   */
  public function setArchivalNote($archival_note);

}
