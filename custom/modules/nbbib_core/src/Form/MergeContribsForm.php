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

    // Load base contributor and generate dynamic title.
    $contrib = BibliographicContributor::load($yabrm_contributor);
    $name = $contrib->getName();
    $form['#title'] = "$name";
    $warning = "
      Merging will delete the selected contributor(s) and reassign all
      bibliographic references to the current contributor. This action cannot be
      undone.
    ";

    // Set up duplicates checkbox set.
    $form['duplicates'] = [
      '#type' => 'checkboxes',
      '#options' => [],
      '#title' => $this->t("Possible duplicates for $name"),
      '#description' => $this->t("<b>WARNING: </b>$warning"),
    ];

    // Query for possible duplicates (same formatted name).
    $query = $this->entityTypeManager->getStorage('yabrm_contributor');

    $results = $query->getQuery()
      ->condition('status', 1)
      ->condition('name', $name)
      ->execute();

    // Populate duplicates checkbox set.
    foreach ($results as $cid) {

      // Only include if not base contributor.
      if ($cid != $yabrm_contributor) {
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

        $form['duplicates']['#options'][$cid] =
          $this->t("<a href='/yabrm/yabrm_contributor/$cid' rel='noopener noreferrer' target='_blank'>$dupe_name</a>");
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
    $form_state->setRedirect('nbbib_core.merge_contribs.confirm', ['yabrm_contributor' => 3863]);
  }

}
