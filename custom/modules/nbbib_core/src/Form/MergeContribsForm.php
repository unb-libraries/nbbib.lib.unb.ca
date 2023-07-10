<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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
    $first = $contrib->getFirstName();
    $last = $contrib->getLastName();
    $inst = $contrib->getInstitutionName();
    $pre = $contrib->getPrefix();
    $suf = $contrib->getSuffix();

    $name = $inst ? $inst : trim("$pre $first $last $suf");

    $form['#title'] = $this->t("Merge duplicates into <i>$name</i>");

    // Set up duplicates checkbox set.
    $form['duplicates'] = [
      '#type' => 'checkboxes',
      '#options' => [],
      '#title' => $this->t("Possible duplicates for $name"),
      '#required' => TRUE,
    ];

    // Query for possible duplicates (same formatted name).
    $query = $this->entityTypeManager->getStorage('yabrm_contributor');
    $results = [];

    // If the target contributor is an institution...
    if ($inst) {
      // Break the name into individual words.
      $tokens = explode(" ", $name);

      // For each word...
      foreach ($tokens as $token) {
        // If the word is longer than 3 characters...
        if (strlen($token) > 3) {
          // Return all contributors that contain the word.
          $set = $query->getQuery()
            ->condition('status', 1)
            ->condition('name', $token, 'CONTAINS')
            ->sort('name', 'asc')
            ->accessCheck(TRUE)
            ->execute();
        }
        // Add to array of all duplicate candidates.
        $candidates = array_merge($results, $set);
      }
    }
    else {
      $candidates = $query->getQuery()
        ->condition('status', 1)
        ->condition('last_name', $last, 'CONTAINS')
        ->sort('name', 'asc')
        ->accessCheck(TRUE)
        ->execute();
    }

    // For each duplicate candidate...
    foreach ($candidates as $candidate) {
      // Load candidate name.
      $obj = BibliographicContributor::load($candidate);
      $name2 = $obj->getName();

      if (!$inst) {
        $name2 = trim(
          $obj->getFirstName() .
          $obj->getLastName()
        );
      }
      // Get the percentage of similar characters in $perc.
      similar_text($name2, $name, $perc);

      // If characters match over 60%...
      if ($perc > 60) {
        // Add to results list.
        $results[] = $candidate;
      }
    }

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
          $pre = $dupe->getPrefix();
          $suf = $dupe->getSuffix();
          $dupe_name = trim("$pre $first $last $suf");
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
