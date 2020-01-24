<?php

namespace Drupal\simple_styleguide\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Table drag example simple form.
 *
 * @ingroup tabledrag_example
 */
class PatternsCollectionForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;
  protected $patternStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * PatternsCollectionForm constructor.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Core entity manager.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(Connection $database, EntityTypeManagerInterface $entityTypeManager) {
    $this->database = $database;
    $this->patternStorage = $entityTypeManager->getStorage('styleguide_pattern');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'patterns_form';
  }

  /**
   * Builds the simple tabledrag form.
   *
   * @param array $form
   *   Render array representing from.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['table-row'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Name'),
        $this->t('Weight'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('Sorry, There are no items!'),
      // TableDrag: Each array value is a list of callback arguments for
      // drupal_add_tabledrag(). The #id of the table is automatically
      // prepended; if there is none, an HTML ID is auto-generated.
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'table-sort-weight',
        ],
      ],
    ];

    // Build the table rows and columns.
    //
    // The first nested level in the render array forms the table row, on which
    // you likely want to set #attributes and #weight.
    // Each child element on the second level represents a table column cell in
    // the respective table row, which are render elements on their own. For
    // single output elements, use the table cell itself for the render element.
    // If a cell should contain multiple elements, simply use nested sub-keys to
    // build the render element structure for the renderer service as you would
    // everywhere else.
    //
    // About the condition id<8:
    // For the purpose of this 'simple table' we are only using the first 8 rows
    // of the database.  The others are for 'nested' example.
    $results = $this->database->select('config', 'c')
      ->fields('c')
      ->condition('c.name', '%simple_styleguide.styleguide_pattern%', 'LIKE')
      ->execute()
      ->fetchAll();

    foreach ($results as $result) {
      $data = unserialize($result->data);
      $id = $data['id'];

      // Set up patterns array.
      $patterns[$id]['id'] = $id;
      $patterns[$id]['label'] = $data['label'];

      if (!empty($data['weight'])) {
        $patterns[$id]['weight'] = $data['weight'];
      }
      else {
        $patterns[$id]['weight'] = 0;
      }
    }

    // Sort patterns by weight.
    usort($patterns, function ($a, $b) {
      return $a['weight'] <=> $b['weight'];
    });

    foreach ($patterns as $pattern) {
      $patternId = $pattern['id'];

      // TableDrag: Mark the table row as draggable.
      $form['table-row'][$patternId]['#attributes']['class'][] = 'draggable';

      // Sort the table row according to its existing/configured weight.
      $form['table-row'][$patternId]['#weight'] = $pattern['weight'];

      $form['table-row'][$patternId]['label'] = [
        '#markup' => $pattern['label'],
      ];

      // TableDrag: Weight column element.
      $form['table-row'][$patternId]['weight'] = [
        '#type' => 'weight',
        '#title' => $this->t('Weight for @title', ['@title' => $pattern['label']]),
        '#title_display' => 'invisible',
        '#default_value' => $pattern['weight'],
        // Classify the weight element for #tabledrag.
        '#attributes' => ['class' => ['table-sort-weight']],
      ];

      $form['table-row'][$patternId]['extra_actions'] = [
        '#type' => 'dropbutton',
        '#dropbutton_type' => 'extrasmall',
        '#attributes' => [
          'id' => 'patterns-extra-actions',
        ],
        '#links' => [
          'edit' => [
            'title' => $this->t('Edit'),
            'url' => Url::fromRoute('entity.styleguide_pattern.edit_form', ['styleguide_pattern' => $patternId]),
          ],
          'delete' => [
            'title' => $this->t('Delete'),
            'url' => Url::fromRoute('entity.styleguide_pattern.delete_form', ['styleguide_pattern' => $patternId]),
          ],
        ],
      ];
    }

    $form['actions'] = ['#type' => 'actions'];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function cancel(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('simple_styleguide.patterns_form');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $submission = $form_state->getValue('table-row');
    foreach ($submission as $patternId => $value) {
      $entity = $this->patternStorage->load($patternId);
      $entity->weight = $value['weight'];
      $entity->save();
    }
  }

}
