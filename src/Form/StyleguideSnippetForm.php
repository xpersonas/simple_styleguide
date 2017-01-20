<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StyleguideSnippetForm.
 *
 * @package Drupal\simple_styleguide\Form
 */
class StyleguideSnippetForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $styleguide_snippet = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $styleguide_snippet->label(),
      '#description' => $this->t("Label for the Styleguide snippet."),
      '#required' => TRUE,
    ];

    $form['snippet'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Snippet'),
      '#rows' => 15,
      '#default_value' => $styleguide_snippet->snippet,
    );

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $styleguide_snippet->id(),
      '#machine_name' => [
        'exists' => '\Drupal\simple_styleguide\Entity\StyleguideSnippet::load',
      ],
      '#disabled' => !$styleguide_snippet->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $styleguide_snippet = $this->entity;
    $status = $styleguide_snippet->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Styleguide snippet.', [
          '%label' => $styleguide_snippet->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Styleguide snippet.', [
          '%label' => $styleguide_snippet->label(),
        ]));
    }
    $form_state->setRedirectUrl($styleguide_snippet->toUrl('collection'));
  }

}
