<?php

namespace Drupal\yabrm\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Contrib Archival revision.
 *
 * @ingroup yabrm
 */
class ContribArchivalRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The Contrib Archival revision.
   *
   * @var \Drupal\yabrm\Entity\ContribArchivalInterface
   */
  protected $revision;

  /**
   * The Contrib Archival storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $contribArchivalStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->contribArchivalStorage = $container->get('entity_type.manager')->getStorage('yabrm_contrib_archival');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'yabrm_contrib_archival_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_contrib_archival.version_history', ['yabrm_contrib_archival' => $this->revision->id()]);
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
  public function buildForm(array $form, FormStateInterface $form_state, $yabrm_contrib_archival_revision = NULL) {
    $this->revision = $this->ContribArchivalStorage->loadRevision($yabrm_contrib_archival_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->ContribArchivalStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')
      ->notice('Contrib Archival: deleted %title revision %revision.', [
        '%title' => $this->revision->label(),
        '%revision' => $this->revision->getRevisionId(),
      ]);
    $this->messenger()
      ->addMessage($this->t('Revision from %revision-date of Contrib Archival %title has been deleted.', [
        '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
        '%title' => $this->revision->label(),
      ]));
    $form_state->setRedirect(
      'entity.yabrm_contrib_archival.canonical',
       ['yabrm_contrib_archival' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {yabrm_contrib_archival_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.yabrm_contrib_archival.version_history',
         ['yabrm_contrib_archival' => $this->revision->id()]
      );
    }
  }

}
