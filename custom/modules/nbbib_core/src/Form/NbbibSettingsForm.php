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
    return 'nbbib_core_admin_settings';
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
    $form['nbbib_essays_header'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Header text for essays view'),
      '#format' => 'unb_libraries',
      '#default_value' => $config->get('view_headers.nbbib_essays'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set the submitted configuration setting.
      ->set('view_headers.nbbib_essays', $form_state->getValue('nbbib_essays_header')['value'])
      ->save();
    
      $form_state->setRedirect('view.nbbib_essays.page_1');
    parent::submitForm($form, $form_state);
  }
}