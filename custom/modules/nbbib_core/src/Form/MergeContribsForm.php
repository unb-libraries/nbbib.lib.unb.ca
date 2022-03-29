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
