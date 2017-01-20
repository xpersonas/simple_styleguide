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

    // Bgn add more field.
    // .........................................................................
    $i = 0;
    $name_field = $form_state->get('num_names');
    $form['#tree'] = TRUE;
    $form['names_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Color Palette'),
      '#prefix' => '<div id="names-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    if (empty($name_field)) {
      $name_field = $form_state->set('num_names', 1);
    }
    for ($i = 0; $i < $name_field; $i++) {
      $form['names_fieldset']['name'][$i] = [
        '#type' => 'textfield',
        '#title' => t('Color'),
        '#description' => t('Use hex values only. For example: #FF0000.'),
      ];
    }
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['names_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => t('Add one more'),
      '#submit' => array('::addOne'),
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'names-fieldset-wrapper',
      ],
    ];
    if ($name_field > 1) {
      $form['names_fieldset']['actions']['remove_name'] = [
        '#type' => 'submit',
        '#value' => t('Remove one'),
        '#submit' => array('::removeCallback'),
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'names-fieldset-wrapper',
        ],
      ];
    }
    // .........................................................................
    // End add more field.
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

    $this->config('simple_styleguide.styleguidesettings')
      ->set(
        'default_segments', $form_state->getValue('default_segments'),
        'name', $form_state->getValue('name')
      )
      ->save();
  }

}
