<?php

namespace Drupal\webform_validation\Form;

use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\webform\Form\WebformDialogFormTrait;
use Drupal\webform\Plugin\WebformElementManagerInterface;
use Drupal\webform\Utility\WebformYaml;
use Drupal\webform\WebformEntityElementsValidatorInterface;
use Drupal\webform\WebformTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebformValidationsForm.
 */
class WebformValidationsForm extends BundleEntityFormBase {

  use WebformDialogFormTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // TODO: this should be the form for the actual validation rule, not the validations.
    $webform = $this->getEntity();
    $elements = $webform->getElementsDecoded();

    $validations = $webform->getThirdPartySettings('webform_validation');

    $components = [];
    foreach ($elements as $key => $value) {
      if (!empty($value['#title'])) {
        $components[$key] = $value['#title'];
      }
    }

    $form = parent::buildForm($form, $form_state);
    $form['components'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Components'),
      '#description' => $this->t('Select the components to be validated by this validation rule'),
      '#options' => $components,
      '#default_value' => $validations['components'] ,
    ];
    $form['rule_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rule name'),
      '#description' => $this->t('Enter a descriptive name for this validation rule'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $validations['rule_name'],
    ];
    $form['custom_error_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom error message'),
      '#description' => $this->t('Specify an error message that should be displayed when user input doesn&#039;t pass validation'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $validations['custom_error_message'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $webform = $this->getEntity();

    $settings = [
      'components',
      'rule_name',
      'custom_error_message',
    ];
    foreach ($settings as $key) {
      $value = $form_state->getValue($key);
      $webform->setThirdPartySetting('webform_validation', $key, $value);
    }

    parent::submitForm($form, $form_state);
  }

}
