<?php

namespace Drupal\yabrm\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Journal Article Reference revision.
 *
 * @ingroup yabrm
 */
class JournalArticleReferenceRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Journal Article Reference revision.
   *
   * @var \Drupal\yabrm\Entity\JournalArticleReferenceInterface
   */
  protected $revision;

  /**
   * The Journal Article Reference storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $journalArticleReferenceStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new JournalArticleReferenceRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->journalArticleReferenceStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager->getStorage('yabrm_journal_article'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yabrm_journal_article_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_journal_article.version_history', ['yabrm_journal_article' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $yabrm_journal_article_revision = NULL) {
    $this->revision = $this->journalArticleReferenceStorage->loadRevision($yabrm_journal_article_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->journalArticleReferenceStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Journal Article Reference: deleted %title revision %revision.', [
      '%title' => $this->revision->label(),
      '%revision' => $this->revision->getRevisionId()
    ]);
    \Drupal::messenger()->addMessage(t('Revision from %revision-date of Journal Article Reference %title has been deleted.', [
      '%revision-date' => \Drupal::service('date.formatter')->format($this->revision->getRevisionCreationTime()),
      '%title' => $this->revision->label()
    ]));
    $form_state->setRedirect(
      'entity.yabrm_journal_article.canonical',
       ['yabrm_journal_article' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {yabrm_journal_article_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.yabrm_journal_article.version_history',
         ['yabrm_journal_article' => $this->revision->id()]
      );
    }
  }

}
