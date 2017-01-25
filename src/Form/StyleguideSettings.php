<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

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

    $form['intro'] = [
      '#markup' => 'Choose any of the default html snippets you would like to see on your styleguide. You can also create custom snippets as needed.',
    ];

    $form['default_snippets'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Default Snippets'),
      '#options' => [
        'headings' => 'headings',
        'text' => 'text',
        'lists' => 'lists',
        'blockquote' => 'blockquote',
        'rule' => 'horizontal rule',
        'table' => 'table',
        'alerts' => 'alerts',
        'breadcrumbs' => 'breadcrumbs',
        'forms' => 'forms',
        'buttons' => 'buttons',
        'pagination' => 'pagination',
      ],
      '#default_value' => (count($config->get('default_snippets')) > 0) ? $config->get('default_snippets') : array(),
    ];

    $button_link = Url::fromRoute('entity.styleguide_snippet.collection')->toString();
    $form['custom'] = [
      '#markup' => '<p><a href="' . $button_link . '" class="button">Create Custom Styleguide Snippets</a></p>',
    ];

    $form['color_palette'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Color Palette'),
    ];
    $form['color_palette']['default_colors'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Hex|Name|Description'),
      '#default_value' => ($config->get('default_colors') ? implode("\r\n", $config->get('default_colors')) : ''),
      '#description' => $this->t('xxSeparate values with a "|". For example:<br/>#FF0000|red|Descriptive text describing how this color will be used.'),
      '#prefix' => $this->t('<p>Create a list of all the colors you would like represented in your styleguide. Each color should be on a separate line. By default, hex values will be used in an inline style for the color palette section of the styleguide.</p>'),
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
    $config->set('default_snippets', $form_state->getValue('default_snippets'));
    $config->set('default_colors', explode("\r\n", $form_state->getValue('default_colors')));

    $config->save();
  }

}
