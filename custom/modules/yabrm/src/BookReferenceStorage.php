<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\BookReferenceInterface;

/**
 * Defines the storage handler class for Book reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Book reference entities.
 *
 * @ingroup yabrm
 */
class BookReferenceStorage extends SqlContentEntityStorage implements BookReferenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(BookReferenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_book_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_book_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(BookReferenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_book_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_book_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->accessCheck(FALSE)
      ->execute();
  }

}
