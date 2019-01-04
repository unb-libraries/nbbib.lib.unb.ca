<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\BibliographicReferenceInterface;

/**
 * Defines the storage handler class for Bibliographic Reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Reference entities.
 *
 * @ingroup yabrm
 */
class BibliographicReferenceStorage extends SqlContentEntityStorage implements BibliographicReferenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BibliographicReferenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_biblio_reference_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_biblio_reference_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BibliographicReferenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_biblio_reference_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_biblio_reference_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
