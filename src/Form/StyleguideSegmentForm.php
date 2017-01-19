<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StyleguideSegmentForm.
 *
 * @package Drupal\simple_styleguide\Form
 */
class StyleguideSegmentForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $styleguide_segment = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $styleguide_segment->label(),
      '#description' => $this->t("Label for the Styleguide segment."),
      '#required' => TRUE,
    ];

    $form['segment'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Segment'),
      '#rows' => 15,
      '#default_value' => $styleguide_segment->segment,
    );

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $styleguide_segment->id(),
      '#machine_name' => [
        'exists' => '\Drupal\simple_styleguide\Entity\StyleguideSegment::load',
      ],
      '#disabled' => !$styleguide_segment->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $styleguide_segment = $this->entity;
    $status = $styleguide_segment->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Styleguide segment.', [
          '%label' => $styleguide_segment->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Styleguide segment.', [
          '%label' => $styleguide_segment->label(),
        ]));
    }
    $form_state->setRedirectUrl($styleguide_segment->toUrl('collection'));
  }

}
