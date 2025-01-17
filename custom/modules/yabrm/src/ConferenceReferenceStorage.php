<?php

namespace Drupal\yabrm;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\yabrm\Entity\ConferenceReferenceInterface;

/**
 * Defines the storage handler class for Conference reference entities.
 *
 * This extends the base storage class, adding required special handling for
 * Conference reference entities.
 *
 * @ingroup yabrm
 */
class ConferenceReferenceStorage extends SqlContentEntityStorage implements ConferenceReferenceStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ConferenceReferenceInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_conference_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {yabrm_conference_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ConferenceReferenceInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {yabrm_conference_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('yabrm_conference_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->accessCheck(FALSE)
      ->execute();
  }

}
