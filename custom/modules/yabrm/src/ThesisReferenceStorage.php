<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\yabrm\Entity\ThesisReferenceInterface;

/**
 * Defines the storage handler class for Thesis reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Thesis reference entities.
 *
 * @ingroup yabrm
 */
class ThesisReferenceStorage extends SqlContentEntityStorage implements ThesisReferenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ThesisReferenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_thesis_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_thesis_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ThesisReferenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_thesis_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_thesis_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
