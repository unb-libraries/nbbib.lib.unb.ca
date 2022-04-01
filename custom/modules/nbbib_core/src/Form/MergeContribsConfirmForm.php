<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\yabrm\Entity\BibliographicContributor;

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
   * For services dependency injection.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
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
    dump($duplicates);

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
    // @todo Merge contributors.
    $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
  }

}
