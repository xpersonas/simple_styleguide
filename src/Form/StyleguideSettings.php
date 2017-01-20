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
      '#description' => $this->t('Each color should be on a separate line.'),
      '#default_value' => ($config->get('default_colors') ? implode("\r\n", $config->get('default_colors')) : ''),

      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
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
