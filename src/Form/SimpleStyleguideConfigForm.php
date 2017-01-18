<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SimpleStyleguideConfigForm.
 *
 * @package Drupal\simple_styleguide\Form
 */
class SimpleStyleguideConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'simple_styleguide.simplestyleguideconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'simple_styleguide_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('simple_styleguide.simplestyleguideconfig');
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 128,
      '#size' => 64,
      '#default_value' => $config->get('title'),
    ];
    $form['markup'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Markup'),
      '#default_value' => $config->get('markup'),
    ];
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

    $this->config('simple_styleguide.simplestyleguideconfig')
      ->set('title', $form_state->getValue('title'))
      ->set('markup', $form_state->getValue('markup'))
      ->save();
  }

}
