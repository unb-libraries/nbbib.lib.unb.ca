<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\PeriodicalReferenceInterface;

/**
 * Defines the storage handler class for Periodical reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Periodical reference entities.
 *
 * @ingroup yabrm
 */
class PeriodicalReferenceStorage extends SqlContentEntityStorage implements PeriodicalReferenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(PeriodicalReferenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_periodical_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_periodical_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(PeriodicalReferenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_periodical_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_periodical_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->accessCheck(FALSE)
      ->execute();
  }

}
