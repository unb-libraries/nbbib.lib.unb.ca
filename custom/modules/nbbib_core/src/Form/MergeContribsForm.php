<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\yabrm\Entity\BibliographicContributor;

/**
 * EditSubjectsForm class.
 */
class MergeContribsForm extends FormBase {
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
    return 'nbbib_core_merge_contribs_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $yabrm_contributor = NULL) {
    $form = [];

    // Generate dynamic title.
    $contrib = BibliographicContributor::load($yabrm_contributor);
    $name = $contrib->getName();
    $form['#title'] = "$name";

    // Set up duplicates checkbox set.
    $form['duplicates'] = [
      '#type' => 'checkboxes',
      '#options' => [],
      '#title' => "Possible duplicates for $name",
    ];

    // Query for possible duplicates (same formatted name).
    $query = $this->entityTypeManager->getStorage('yabrm_contributor');

    $results = $query->getQuery()
      ->condition('status', 1)
      ->condition('name', $name)
      ->execute();

    // Populate duplicates checkbox set.
    foreach ($results as $cid) {
      $dupe = BibliographicContributor::load($cid);
      $first = $dupe->getFirstName();
      $last = $dupe->getLastName();
      $inst = $dupe->getInstitutionName();

      if ($inst) {
        $dupe_name = trim($inst);
      }
      else {
        $dupe_name = trim("$first $last");
      }

      $form['duplicates']['#options'][$cid] = $this->t($dupe_name);
    }

    return $form;
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
  }

}
