<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\BibliographicContributorArchivalInterface;

/**
 * Defines the storage handler class for Bibliographic Contributor Archival entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Contributor Archival entities.
 *
 * @ingroup yabrm
 */
class BibliographicContributorArchivalStorage extends SqlContentEntityStorage implements BibliographicContributorArchivalStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BibliographicContributorArchivalInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_contributor_archival_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_contributor_archival_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BibliographicContributorArchivalInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_contributor_archival_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_contributor_archival_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
