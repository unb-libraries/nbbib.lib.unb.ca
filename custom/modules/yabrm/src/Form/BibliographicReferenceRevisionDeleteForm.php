<?php

namespace Drupal\yabrm\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Bibliographic Reference revision.
 *
 * @ingroup yabrm
 */
class BibliographicReferenceRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Bibliographic Reference revision.
   *
   * @var \Drupal\yabrm\Entity\BibliographicReferenceInterface
   */
  protected $revision;

  /**
   * The Bibliographic Reference storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $bibliographicReferenceStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new BibliographicReferenceRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The database connection.
   */
  public function __construct(
    EntityStorageInterface $entity_storage,
    Connection $connection,
    DateFormatterInterface $date_formatter) {
    $this->bibliographicReferenceStorage = $entity_storage;
    $this->connection = $connection;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager->getStorage('yabrm_biblio_reference'),
      $container->get('database'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yabrm_biblio_reference_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_biblio_reference.version_history', ['yabrm_biblio_reference' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $yabrm_biblio_reference_revision = NULL) {
    $this->revision = $this->bibliographicReferenceStorage->loadRevision($yabrm_biblio_reference_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->bibliographicReferenceStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Bibliographic Reference: deleted %title revision %revision.', [
      '%title' => $this->revision->label(),
      '%revision' => $this->revision->getRevisionId(),
    ]);
    $this->messenger()->addMessage($this->t('Revision from %revision-date of Bibliographic Reference %title has been deleted.', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
      '%title' => $this->revision->label(),
    ]));
    $form_state->setRedirect(
      'entity.yabrm_biblio_reference.canonical',
       ['yabrm_biblio_reference' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {yabrm_biblio_reference_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.yabrm_biblio_reference.version_history',
         ['yabrm_biblio_reference' => $this->revision->id()]
      );
    }
  }

}
