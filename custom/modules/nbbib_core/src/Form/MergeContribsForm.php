<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EditSubjectsForm class.
 */
class MergeContribsForm extends FormBase {
  /**
   * ID of the item to merge.
   *
   * @var int
   */
  protected $cid;

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
    $this->cid = $yabrm_contributor;

    // Load base contributor and generate dynamic title.
    $contrib = BibliographicContributor::load($yabrm_contributor);
    $name = $contrib->getName();
    $last_name = $contrib->getLastName();
    $form['#title'] = "$name";

    // Set up duplicates checkbox set.
    $form['duplicates'] = [
      '#type' => 'checkboxes',
      '#options' => [],
      '#title' => $this->t("Possible duplicates for $name"),
      '#required' => TRUE,
    ];

    // Query for possible duplicates (same formatted name).
    $query = $this->entityTypeManager->getStorage('yabrm_contributor');

    $results = $query->getQuery()
      ->condition('status', 1)
      ->condition('last_name', $last_name)
      ->sort('name', 'asc')
      ->execute();

    // Populate duplicates checkbox set.
    foreach ($results as $cid) {

      // Only include if not base contributor.
      if ($cid != $yabrm_contributor) {
        $dupe = BibliographicContributor::load($cid);
        $inst = $dupe->getInstitutionName();

        if ($inst) {
          $dupe_name = trim($inst);
        }
        else {
          $first = $dupe->getFirstName();
          $last = $dupe->getLastName();
          $dupe_name = trim("$first $last");
        }

        $form['duplicates']['#options'][$cid] =
          $this->t(
            "<span style='display: table-cell; width: 16rem;'>$dupe_name</span> [
            <a href='/yabrm/yabrm_contributor/$cid' class='use-ajax' data-dialog-options='{&quot;width&quot;:500}' data-dialog-type='modal'>
              Overview
            </a>] [
            <a href='/contributor/$cid/merge'>
              Switch to this contributor
            </a>]
            "
          );
      }
    }

    if (count($results) > 1) {
      $form['actions']['#type'] = 'actions';

      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Merge'),
        '#button_type' => 'primary',
      ];
    }
    else {
      $form['duplicates']['#description'] =
        $this->t("No duplicate candidates found.");
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
    // Redirect to confirm form.
    $form_state->setRedirect('nbbib_core.merge_contribs.confirm', [
      'yabrm_contributor' => $this->cid,
      'duplicates' => implode('-', $form_state->getValue('duplicates')),
    ]);
  }

}
