<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Report reference entities.
 *
 * @ingroup yabrm
 */
interface ReportReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the report institution.
   *
   * @return string
   *   The institution of the report.
   */
  public function getInstitution();

  /**
   * Sets the institution of the report.
   *
   * @param string $institution
   *   The institution of the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setInstitution($institution);

  /**
   * Gets the number of pages in the report.
   *
   * @return string
   *   The number of pages in the report.
   */
  public function getNumberOfPages();

  /**
   * Sets the number of pages in the report.
   *
   * @param string $num_pages
   *   The number of pages in the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setNumberOfPages($num_pages);

  /**
   * Gets the series of the report.
   *
   * @return string
   *   The series of the report.
   */
  public function getSeries();

  /**
   * Sets the series of the report.
   *
   * @param string $series
   *   The series of the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setSeries($series);

  /**
   * Gets the type of the report.
   *
   * @return string
   *   The type of the report.
   */
  public function getReportType();

  /**
   * Sets the type of the report.
   *
   * @param string $report_type
   *   The type of the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setReportType($report_type);

  /**
   * Gets the number of the report.
   *
   * @return string
   *   The number of the report.
   */
  public function getReportNumber();

  /**
   * Sets the number of the report.
   *
   * @param string $report_number
   *   The number of the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setReportNumber($report_number);

  /**
   * Gets the place of the report.
   *
   * @return string
   *   The place of the report.
   */
  public function getPlace();

  /**
   * Sets the place of the report.
   *
   * @param string $place
   *   The place of the report.
   *
   * @return \Drupal\yabrm\Entity\ReportReferenceInterface
   *   The called Report Reference entity.
   */
  public function setPlace($place);

}
