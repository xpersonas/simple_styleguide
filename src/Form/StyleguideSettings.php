<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StyleguideSettings.
 *
 * @package Drupal\simple_styleguide\Form
 */
class StyleguideSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'simple_styleguide.styleguidesettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'styleguide_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('simple_styleguide.styleguidesettings');

    $form['default_segments'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Default Segments'),
      '#options' => [
        'alerts' => 'alerts',
        'blockquote' => 'blockquote',
        'breadcrumbs' => 'breadcrumbs',
        'buttons' => 'buttons',
        'colors' => 'colors',
        'forms' => 'forms',
        'headings' => 'headings',
        'icons' => 'icons',
        'images' => 'images',
        'lists' => 'lists',
        'media' => 'media',
        'pagination' => 'pagination',
        'rule' => 'rule',
        'table' => 'table',
        'text' => 'text',
      ],
      '#default_value' => $config->get('default_segments'),
    ];

    $form['default_colors'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Color Palette'),
      '#description' => $this->t('Each color should be on a separate line. Values should start with a hashtag. For example: #FF0000.'),
      '#default_value' => ($config->get('default_colors') ? implode("\r\n", $config->get('default_colors')) : ''),
      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * Callback for both ajax-enabled buttons.
   *
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    return $form['names_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    $add_button = $name_field + 1;
    $form_state->set('num_names', $add_button);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_names', $remove_button);
    }
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('simple_styleguide.styleguidesettings');
    $config->set('default_segments', $form_state->getValue('default_segments'));
    $config->set('default_colors', explode("\r\n", $form_state->getValue('default_colors')));
    $config->save();
  }

}