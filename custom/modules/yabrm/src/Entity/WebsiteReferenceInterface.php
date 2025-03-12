<?php

namespace Drupal\yabrm\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Website reference entities.
 *
 * @ingroup yabrm
 */
interface WebsiteReferenceInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the number of pages in the website.
   *
   * @return string
   *   The number of pages in the website.
   */
  public function getOrganization();

  /**
   * Sets the number of pages in the website.
   *
   * @param string $organization
   *   The number of pages in the website.
   *
   * @return \Drupal\yabrm\Entity\WebsiteReferenceInterface
   *   The called Website Reference entity.
   */
  public function setOrganization($organization);

  /**
   * Gets the site url of the website.
   *
   * @return string
   *   The site url of the website.
   */
  public function getSiteUrl();

  /**
   * Sets the site url of the website.
   *
   * @param string $site_url
   *   The site url of the website.
   *
   * @return \Drupal\yabrm\Entity\WebsiteReferenceInterface
   *   The called Website Reference entity.
   */
  public function setSiteUrl($site_url);

  /**
   * Gets the last revision date.
   *
   * @return string
   *   The last revision date.
   */
  public function getRevised();

  /**
   * Sets the last revision date.
   *
   * @param string $revision
   *   The last revision date.
   *
   * @return \Drupal\yabrm\Entity\WebsiteReferenceInterface
   *   The called Website Reference entity.
   */
  public function setRevised($revision);

}
