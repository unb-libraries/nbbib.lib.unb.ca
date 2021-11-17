<?php

namespace Drupal\yabrm\Form;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BibliographicCollectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for reverting a Bibliographic Collection revision.
 *
 * @ingroup yabrm
 */
class BibliographicCollectionRevisionRevertForm extends ConfirmFormBase {


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
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The service container.
   *
   * @var \Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $service;

  /**
   * Constructs a new BibliographicCollectionRevisionRevertForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The Bibliographic Collection storage.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $service
   *   The service container.
   */
  public function __construct(
    EntityStorageInterface $entity_storage,
    DateFormatterInterface $date_formatter,
    ContainerInterface $service
    ) {
    $this->bibliographicCollectionStorage = $entity_storage;
    $this->dateFormatter = $date_formatter;
    $this->service = $service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')->getStorage('yabrm_collection'),
      $container->get('date.formatter'),
      $container->get('service_container')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yabrm_collection_revision_revert_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to revert to the revision from %revision-date?', ['%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime())]);
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
    return $this->t('Revert');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
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
    // The revision timestamp will be updated when the revision is saved. Keep
    // the original one for the confirmation message.
    $original_revision_timestamp = $this->revision->getRevisionCreationTime();

    $this->revision = $this->prepareRevertedRevision($this->revision, $form_state);
    $this->revision->revision_log = $this->t('Copy of the revision from %date.', ['%date' => $this->dateFormatter->format($original_revision_timestamp)]);
    $this->revision->save();

    $this->logger('content')->notice('Bibliographic Collection: reverted %title revision %revision.', [
      '%title' => $this->revision->label(),
      '%revision' => $this->revision->getRevisionId(),
    ]);
    $this->messenger()->addMessage($this->t('Bibliographic Collection %title has been reverted to the revision from %revision-date.', [
      '%title' => $this->revision->label(),
      '%revision-date' => $this->dateFormatter->format($original_revision_timestamp),
    ]));
    $form_state->setRedirect(
      'entity.yabrm_collection.version_history',
      ['yabrm_collection' => $this->revision->id()]
    );
  }

  /**
   * Prepares a revision to be reverted.
   *
   * @param \Drupal\yabrm\Entity\BibliographicCollectionInterface $revision
   *   The revision to be reverted.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\yabrm\Entity\BibliographicCollectionInterface
   *   The prepared revision ready to be stored.
   */
  protected function prepareRevertedRevision(BibliographicCollectionInterface $revision, FormStateInterface $form_state) {
    $revision->setNewRevision();
    $revision->isDefaultRevision(TRUE);
    $revision->setRevisionCreationTime($this->service->get('datetime.time')->getRequestTime());

    return $revision;
  }

}
