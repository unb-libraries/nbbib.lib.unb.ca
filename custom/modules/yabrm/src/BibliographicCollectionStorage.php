<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\BibliographicCollectionInterface;

/**
 * Defines the storage handler class for Bibliographic Collection entities.
 *
 * This extends the base storage class, adding required special handling for
 * Bibliographic Collection entities.
 *
 * @ingroup yabrm
 */
class BibliographicCollectionStorage extends SqlContentEntityStorage implements BibliographicCollectionStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BibliographicCollectionInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_collection_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_collection_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BibliographicCollectionInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_collection_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_collection_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
