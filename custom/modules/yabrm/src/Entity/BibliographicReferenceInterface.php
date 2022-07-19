<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bibliographic Reference entities.
 *
 * @ingroup yabrm
 */
interface BibliographicReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bibliographic Reference name.
   *
   * @return string
   *   Name of the Bibliographic Reference.
   */
  public function getName();

  /**
   * Sets the Bibliographic Reference name.
   *
   * @param string $name
   *   The Bibliographic Reference name.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setName($name);

  /**
   * Gets the Bibliographic Reference creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bibliographic Reference.
   */
  public function getCreatedTime();

  /**
   * Sets the Bibliographic Reference creation timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Reference creation timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Bibliographic Reference creation timestamp.
   *
   * @return int
   *   Change timestamp of the Bibliographic Reference.
   */
  public function getChangedTime();

  /**
   * Sets the Bibliographic Reference change timestamp.
   *
   * @param int $timestamp
   *   The Bibliographic Reference change timestamp.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setChangedTime($timestamp);

  /**
   * Returns the Bibliographic Reference published status indicator.
   *
   * Unpublished Bibliographic Reference are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bibliographic Reference is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bibliographic Reference.
   *
   * @param bool $published
   *   TRUE if published, FALSE if unpublished.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublished($published);

  /**
   * Gets the Bibliographic Reference revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Bibliographic Reference revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Get the Bibliographic Reference revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Bibliographic Reference revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setRevisionUserId($uid);

  /**
   * Gets the contributors associated with this bibliographic reference.
   *
   * @param string $role
   *   Only return contributors matching this role. Ignored if NULL.
   *
   * @return \Drupal\yabrm\Entity\BibliographicContributor[]
   *   The contributors.
   */
  public function getContributors($role);

  /**
   * Sets the Bibliographic contributors.
   *
   * @param \Drupal\paragraphs\Entity\Paragraph[] $contributors
   *   An array of contributor paragraphs entities.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setContributors(array $contributors);

  /**
   * Gets the sortable timestamp of the reference.
   *
   * @return int
   *   The UNIX epoch timestamp approximation for the reference date.
   */
  public function getSortTimestamp();

  /**
   * Gets the display date of the reference.
   *
   * @return string
   *   The constructed display date of the reference.
   */
  public function getDisplayDate();

  /**
   * Gets the publication year of the reference.
   *
   * @return int
   *   The publication year of the reference.
   */
  public function getPublicationYear();

  /**
   * Sets the publication year of the reference.
   *
   * @param int $year
   *   Integer publication year.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationYear($year);

  /**
   * Gets the numeric publication month of the reference.
   *
   * @return int
   *   The numeric publication month of the reference.
   */
  public function getPublicationMonth();

  /**
   * Sets the publication month of the reference.
   *
   * @param int $month
   *   Integer publication month.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationMonth($month);

  /**
   * Gets the numeric publication day of the reference.
   *
   * @return int
   *   The numeric publication day of the reference.
   */
  public function getPublicationDay();

  /**
   * Sets the publication day of the reference.
   *
   * @param int $day
   *   Integer publication day.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPublicationDay($day);

  /**
   * Gets the Bibliographic Reference language.
   *
   * @return string
   *   Language of the Bibliographic Reference.
   */
  public function getLanguage();

  /**
   * Sets the Bibliographic Reference language.
   *
   * @param string $language
   *   The Bibliographic Reference language.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setLanguage($language);

  /**
   * Gets the Bibliographic Reference title.
   *
   * @return string
   *   Title of the Bibliographic Reference.
   */
  public function getTitle();

  /**
   * Sets the Bibliographic Reference title.
   *
   * @param string $title
   *   The Bibliographic Reference title.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setTitle($title);

  /**
   * Gets the Bibliographic Reference short title.
   *
   * @return string
   *   Short title of the Bibliographic Reference.
   */
  public function getShortTitle();

  /**
   * Sets the Bibliographic Reference title.
   *
   * @param string $short_title
   *   The Bibliographic Reference short title.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setShortTitle($short_title);

  /**
   * Gets the Bibliographic Reference external key reference.
   *
   * @return string
   *   External key reference of the Bibliographic Reference.
   */
  public function getExternalKeyRef();

  /**
   * Sets the Bibliographic Reference external key reference.
   *
   * @param string $external_key_ref
   *   The Bibliographic Reference external key reference.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setExternalKeyRef($external_key_ref);

  /**
   * Gets the Bibliographic Reference URL.
   *
   * @return array
   *   URL of the Bibliographic Reference.
   */
  public function getUrl();

  /**
   * Sets the Bibliographic Reference URL.
   *
   * @param array $url
   *   The Bibliographic Reference URL.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setUrl(array $url);

  /**
   * Gets the Bibliographic Reference Abstract Note.
   *
   * @return string
   *   Abstract note of the Bibliographic Reference.
   */
  public function getAbstractNote();

  /**
   * Sets the Bibliographic Reference Abstract Note.
   *
   * @param string $abstract_note
   *   The Bibliographic Reference Abstract Note.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setAbstractNote($abstract_note);

  /**
   * Gets the publisher of the reference.
   *
   * @return string
   *   The publisher of the reference.
   */
  public function getPublisher();

  /**
   * Sets the publisher of the reference.
   *
   * @param string $publisher
   *   The publisher of the reference.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Book Reference entity.
   */
  public function setPublisher($publisher);

  /**
   * Gets the Bibliographic Reference Rights.
   *
   * @return string
   *   Rights of the Bibliographic Reference.
   */
  public function getRights();

  /**
   * Sets the Bibliographic Reference Rights.
   *
   * @param string $rights
   *   The Bibliographic Reference Rights.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setRights($rights);

  /**
   * Gets the Bibliographic Reference Archive.
   *
   * @return \Drupal\taxonomy\TermInterface[]
   *   The archive(s) of the Bibliographic Reference.
   */
  public function getArchive();

  /**
   * Sets the Bibliographic Reference Archive.
   *
   * @param \Drupal\taxonomy\TermInterface[] $archive
   *   The Bibliographic Reference archive(s).
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setArchive(array $archive);

  /**
   * Gets the Bibliographic Reference Archive Location.
   *
   * @return string
   *   The archive location of the Bibliographic Reference.
   */
  public function getArchiveLocation();

  /**
   * Sets the Bibliographic Reference Archive Location.
   *
   * @param string $archive_location
   *   The Bibliographic Reference archive location.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setArchiveLocation($archive_location);

  /**
   * Gets the Bibliographic Reference Archive Library Catalog.
   *
   * @return string
   *   The library catalog of the Bibliographic Reference.
   */
  public function getLibraryCatalog();

  /**
   * Sets the Bibliographic Reference Archive Library Catalog.
   *
   * @param string $library_catalog
   *   The Bibliographic Reference archive location.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setLibraryCatalog($library_catalog);

  /**
   * Gets the Bibliographic Reference Archive Call Number.
   *
   * @return string
   *   The call number of the Bibliographic Reference.
   */
  public function getCallNumber();

  /**
   * Sets the Bibliographic Reference Archive call number.
   *
   * @param string $call_number
   *   The Bibliographic Reference call number.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setCallNumber($call_number);

  /**
   * Gets the Bibliographic Reference Extra Information.
   *
   * @return string
   *   The extra information of the Bibliographic Reference.
   */
  public function getExtra();

  /**
   * Sets the Bibliographic Reference Extra Information.
   *
   * @param string $extra
   *   The Bibliographic Reference extra.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setExtra($extra);

  /**
   * Gets the Bibliographic Reference Physical Description.
   *
   * @return string
   *   The notes of the Bibliographic Reference.
   */
  public function getPhysicalDescription();

  /**
   * Sets the Bibliographic Reference Physical Description.
   *
   * @param string $physical_description
   *   The Bibliographic Reference Physical Description.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setPhysicalDescription($physical_description);

  /**
   * Gets the Bibliographic Reference Private Notes.
   *
   * @return string
   *   The notes of the Bibliographic Reference.
   */
  public function getNotesPrivate();

  /**
   * Sets the Bibliographic Reference Private Notes.
   *
   * @param string $notes_private
   *   The Bibliographic Reference notes.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setNotesPrivate($notes_private);

  /**
   * Gets the Bibliographic Reference Notes.
   *
   * @return string
   *   The notes of the Bibliographic Reference.
   */
  public function getNotes();

  /**
   * Sets the Bibliographic Reference Notes.
   *
   * @param string $notes
   *   The Bibliographic Reference notes.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setNotes($notes);

  /**
   * Gets the Bibliographic Reference Topics.
   *
   * @return \Drupal\taxonomy\TermInterface[]
   *   The topics of the Bibliographic Reference.
   */
  public function getTopics();

  /**
   * Sets the Bibliographic Reference Topics.
   *
   * @param \Drupal\taxonomy\TermInterface[] $topics
   *   An array of taxonomy topics.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setTopics(array $topics);

  /**
   * Gets the Bibliographic Reference Collections.
   *
   * @return \Drupal\taxonomy\TermInterface[]
   *   The collections of the bibliographic reference.
   */
  public function getCollections();

  /**
   * Sets the Bibliographic Reference Collections.
   *
   * @param \Drupal\taxonomy\TermInterface[] $collections
   *   An array of taxonomy terms.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setCollections(array $collections);

  /**
   * Gets the Bibliographic Reference NB Imprint.
   *
   * @return bool
   *   The NB Imprint of the Bibliographic Reference.
   */
  public function getNbImprint();

  /**
   * Sets the Bibliographic Reference NB Imprint.
   *
   * @param bool $nb_imprint
   *   The NB Imprint of the Bibliographic Reference.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReference
   *   The called Bibliographic Reference entity.
   */
  public function setNbImprint($nb_imprint);

  /**
   * Gets the Bibliographic Reference Cover Image.
   *
   * @return array
   *   The 'values' array for a Drupal\image\Plugin\Field\FieldType\ImageItem.
   */
  public function getCover();

  /**
   * Sets the Bibliographic Reference Cover Image.
   *
   * @param mixed[] $values
   *   The 'values' array for a Drupal\image\Plugin\Field\FieldType\ImageItem.
   *
   * @return \Drupal\yabrm\Entity\BibliographicReferenceInterface
   *   The called Bibliographic Reference entity.
   */
  public function setCover(array $values);

}
