<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for this site.
 */
class NbbibSettingsForm extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'nbbib_core.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nbbib_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    // Retrieve reference types and format as string for text field. 
    $types = $config->get('nbbib_types');
    $lines = [];

    foreach ($types as $type) {
      $lines[] = implode(':', array_values($type));
    }

    $types = implode("\n", array_values($lines));

    $form['#title'] = $this->t('NBBIB Settings');

    $form['nbbib_types'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Valid NBBIB reference types'),
      '#description' => $this->t('Each type must be entered in a new line as entity_id:singular:plural (colon-separated, ' . 
        'e.g. yabrm_book_sections:Book Section:Book Sections). <i><b>Caution: Malformed entries can cause site-wide issues.</b></i>'),
      '#rows' => 12,
      '#default_value' => $types,
    ];
    $form['nbbib_essays_header'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Custom header for Essays list page'),
      '#format' => 'unb_libraries',
      '#default_value' => $config->get('view_headers.nbbib_essays'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Transform text back into configuration array.
    $types = $form_state->getValue('nbbib_types');
    $lines = str_replace("\r", '', explode("\n", $types));
    $types = [];

    foreach ($lines as $line) {
      $tokens = explode(':', $line);

      if (!empty($tokens)) {
        $types[] = [
          'entity' => $tokens[0],
          'singular' => $tokens[1],
          'plural' => str_replace("\r", '', $tokens[2]),
        ];
      }
    }

    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('nbbib_types', $types)
      ->set('view_headers.nbbib_essays', $form_state->getValue('nbbib_essays_header')['value'])
      ->save();
    
    $form_state->setRedirect('view.nbbib_essays.page_1');
    parent::submitForm($form, $form_state);
  }
}