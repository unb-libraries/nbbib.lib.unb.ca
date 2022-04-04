<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EditSubjectsForm class.
 */
class MergeContribsConfirmForm extends ConfirmFormBase {
  /**
   * ID of the contributor being merged into.
   *
   * @var int
   */
  protected $cid;

  /**
   * IDs of the duplicate contributors selected for merging.
   *
   * @var string
   */
  protected $duplicates;

  /**
   * The Entity Type Manager service.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The entity type manager service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
  }

  /**
   * Object create method.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nbbib_core_merge_contribs_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want want to merge contributors?');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    $yabrm_contributor = NULL,
    $duplicates = NULL) {

    $this->cid = $yabrm_contributor;
    $this->duplicates = $duplicates;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Extract individual duplicate ids.
    $dids = explode('-', $this->duplicates);

    // For each duplicate id...
    foreach ($dids as $did) {
      // Query for reference relationships (paragraphs) that reference id.
      $query = $this->entityTypeManager->getStorage('paragraph');

      $paragraphs = $query->getQuery()
        ->condition('field_yabrm_contributor_person', $did)
        ->execute();

      // For each paragraph...
      foreach ($paragraphs as $pid) {
        // Query for book references that contain the paragraphs.
        $query = $this->entityTypeManager->getStorage('yabrm_book');

        $books = $query->getQuery()
          ->condition('contributors', $pid, 'IN')
          ->execute();
      }
    }

    $this->messenger->addMessage(implode(', ', $books));
    $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
  }

}
