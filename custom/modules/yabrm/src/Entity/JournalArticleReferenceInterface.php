<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Journal Article Reference entities.
 *
 * @ingroup yabrm
 */
interface JournalArticleReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the journal article publication title.
   *
   * @return string
   *   The publication title of the journal article.
   */
  public function getPublicationTitle();

  /**
   * Sets the journal article publication title.
   *
   * @param string $publication_title
   *   The publication title of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setPublicationTitle($publication_title);

  /**
   * Gets the journal article ISSN.
   *
   * @return string
   *   The ISSN of the journal article.
   */
  public function getIssn();

  /**
   * Sets the journal article ISSN.
   *
   * @param string $issn
   *   The ISSN of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setIssn($issn);

  /**
   * Gets the journal article DOI.
   *
   * @return string
   *   The DOI of the journal article.
   */
  public function getDoi();

  /**
   * Sets the journal article DOI.
   *
   * @param string $doi
   *   The DOI of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setDoi($doi);

  /**
   * Gets the journal article pages.
   *
   * @return string
   *   The pages of the journal article.
   */
  public function getPages();

  /**
   * Sets the journal article pages.
   *
   * @param string $pages
   *   The pages of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setPages($pages);

  /**
   * Gets the journal article issue.
   *
   * @return string
   *   The issue of the journal article.
   */
  public function getIssue();

  /**
   * Sets the journal article issue.
   *
   * @param string $issue
   *   The issue of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setIssue($issue);

  /**
   * Gets the journal article volume.
   *
   * @return string
   *   The volume of the journal article.
   */
  public function getVolume();

  /**
   * Sets the journal article volume.
   *
   * @param string $volume
   *   The volume of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setVolume($volume);

  /**
   * Gets the journal article journal abbreviation.
   *
   * @return string
   *   The journal abbreviation of the journal article.
   */
  public function getJournalAbbreviation();

  /**
   * Sets the journal article journal abbreviation.
   *
   * @param string $journal_abbr
   *   The journal abbreviation of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setJournalAbbreviation($journal_abbr);

  /**
   * Gets the journal article series.
   *
   * @return string
   *   The series of the journal article.
   */
  public function getSeries();

  /**
   * Sets the journal article series.
   *
   * @param string $series
   *   The series of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setSeries($series);

  /**
   * Gets the journal article series text.
   *
   * @return string
   *   The series text of the journal article.
   */
  public function getSeriesText();

  /**
   * Sets the journal article series text.
   *
   * @param string $series_text
   *   The series text of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setSeriesText($series_text);

  /**
   * Gets the journal article series title.
   *
   * @return string
   *   The series title of the journal article.
   */
  public function getSeriesTitle();

  /**
   * Sets the journal article series title.
   *
   * @param string $series_title
   *   The series title of the journal article.
   *
   * @return \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   *   The called Journal Article Reference entity.
   */
  public function setSeriesTitle($series_title);

}
