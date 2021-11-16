<?php

namespace Drupal\yabrm\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Bibliographic Collection revision.
 *
 * @ingroup yabrm
 */
class BibliographicCollectionRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Bibliographic Collection revision.
   *
   * @var \Drupal\yabrm\Entity\BibliographicCollectionInterface
   */
  protected $revision;

  /**
   * The Bibliographic Collection storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $bibliographicCollectionStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * The service container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $service;

  /**
   * Constructs a new BibliographicCollectionRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   * @param Symfony\Component\DependencyInjection\ContainerInterface $service
   *   The service container.
   */
  public function __construct(
    EntityStorageInterface $entity_storage,
    Connection $connection,
    ContainerInterface $service) {
    $this->bibliographicCollectionStorage = $entity_storage;
    $this->connection = $connection;
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity_type.manager');
    return new static(
      $entity_manager->getStorage('yabrm_collection'),
      $container->get('database'),
      $container->get('service_container')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yabrm_collection_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => $this->service->get('date.formatter')->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_collection.version_history', ['yabrm_collection' => $this->revision->id()]);
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
  public function buildForm(array $form, FormStateInterface $form_state, $yabrm_collection_revision = NULL) {
    $this->revision = $this->bibliographicCollectionStorage->loadRevision($yabrm_collection_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->bibliographicCollectionStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Bibliographic Collection: deleted %title revision %revision.', [
      '%title' => $this->revision->label(),
      '%revision' => $this->revision->getRevisionId(),
    ]);
    $this->messenger()->addMessage($this->t('Revision from %revision-date of Bibliographic Collection %title has been deleted.', [
      '%revision-date' => $this->service->get('date.formatter')->format($this->revision->getRevisionCreationTime()),
      '%title' => $this->revision->label(),
    ]));
    $form_state->setRedirect(
      'entity.yabrm_collection.canonical',
       ['yabrm_collection' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {yabrm_collection_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.yabrm_collection.version_history',
         ['yabrm_collection' => $this->revision->id()]
      );
    }
  }

}
