<?php

namespace Drupal\webform_validation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class WebformValidationsForm.
 */
class WebformValidationsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'webform_validation.webformvalidations',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_validations_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('webform_validation.webformvalidations');
    $form['components'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Components'),
      '#description' => $this->t('Select the components to be validated by this validation rule'),
      '#options' => ['a' => $this->t('a'), 'b' => $this->t('b')],
      '#default_value' => $config->get('components'),
    ];
    $form['rule_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rule name'),
      '#description' => $this->t('Enter a descriptive name for this validation rule'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('rule_name'),
    ];
    $form['custom_error_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom error message'),
      '#description' => $this->t('Specify an error message that should be displayed when user input doesn&#039;t pass validation'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('custom_error_message'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('webform_validation.webformvalidations')
      ->set('components', $form_state->getValue('components'))
      ->set('rule_name', $form_state->getValue('rule_name'))
      ->set('custom_error_message', $form_state->getValue('custom_error_message'))
      ->save();
  }

}
