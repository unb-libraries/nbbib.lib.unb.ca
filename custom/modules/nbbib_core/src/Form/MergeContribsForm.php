<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\yabrm\Entity\BibliographicContributor;

/**
 * EditSubjectsForm class.
 */
class MergeContribsForm extends FormBase {

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
    $query = \Drupal::entityQuery('yabrm_contributor')
      ->condition('status', 1)
      ->condition('name', $name);

    $results = $query->execute();

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
