<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Class StyleguidePatternForm.
 *
 * @package Drupal\simple_styleguide\Form
 */
class StyleguidePatternForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function getListUrl() {
    return new Url('simple_styleguide.patterns_form');
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $styleguide_pattern = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $styleguide_pattern->label(),
      '#description' => $this->t("Label for the Styleguide pattern."),
      '#required' => TRUE,
    ];

    $form['pattern'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Pattern'),
      '#rows' => 15,
      '#default_value' => $styleguide_pattern->pattern,
    ];

    $form['weight'] = [
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#rows' => 15,
      '#default_value' => ($styleguide_pattern->weight ?: 0),
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $styleguide_pattern->id(),
      '#machine_name' => [
        'exists' => '\Drupal\simple_styleguide\Entity\StyleguidePattern::load',
      ],
      '#disabled' => !$styleguide_pattern->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $styleguide_pattern = $this->entity;
    $status = $styleguide_pattern->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Styleguide pattern.', [
          '%label' => $styleguide_pattern->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Styleguide pattern.', [
          '%label' => $styleguide_pattern->label(),
        ]));
    }

    $form_state->setRedirectUrl($this->getListUrl());
  }

}
