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
   * Element info manager.
   *
   * @var \Drupal\Core\Render\ElementInfoManagerInterface
   */
  protected $elementInfo;

  /**
   * Webform element manager.
   *
   * @var \Drupal\webform\Plugin\WebformElementManagerInterface
   */
  protected $elementManager;

  /**
   * Webform element validator.
   *
   * @var \Drupal\webform\WebformEntityElementsValidatorInterface
   */
  protected $elementsValidator;

  /**
   * The webform token manager.
   *
   * @var \Drupal\webform\WebformTokenManagerInterface
   */
  protected $tokenManager;

  /**
   * Constructs a WebformEntityElementsForm.
   *
   * @param \Drupal\Core\Render\ElementInfoManagerInterface $element_info
   *   The element manager.
   * @param \Drupal\webform\Plugin\WebformElementManagerInterface $element_manager
   *   The webform element manager.
   * @param \Drupal\webform\WebformEntityElementsValidatorInterface $elements_validator
   *   Webform element validator.
   * @param \Drupal\webform\WebformTokenManagerInterface $token_manager
   *   The webform token manager.
   */
  public function __construct(ElementInfoManagerInterface $element_info, WebformElementManagerInterface $element_manager, WebformEntityElementsValidatorInterface $elements_validator, WebformTokenManagerInterface $token_manager) {
    $this->elementInfo = $element_info;
    $this->elementManager = $element_manager;
    $this->elementsValidator = $elements_validator;
    $this->tokenManager = $token_manager;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.element_info'),
      $container->get('plugin.manager.webform.element'),
      $container->get('webform.elements_validator'),
      $container->get('webform.token_manager')
    );
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

    $webform = $this->getEntity();
    $elements = $webform->getElementsDecoded();
    ksm($elements);

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
//      '#default_value' => $config->get('components'),
    ];
    $form['rule_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rule name'),
      '#description' => $this->t('Enter a descriptive name for this validation rule'),
      '#maxlength' => 64,
      '#size' => 64,
//      '#default_value' => $config->get('rule_name'),
    ];
    $form['custom_error_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom error message'),
      '#description' => $this->t('Specify an error message that should be displayed when user input doesn&#039;t pass validation'),
      '#maxlength' => 255,
      '#size' => 64,
//      '#default_value' => $config->get('custom_error_message'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
  }

}
