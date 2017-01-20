<?php

namespace Drupal\simple_styleguide\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Styleguide snippet entity.
 *
 * @ConfigEntityType(
 *   id = "styleguide_snippet",
 *   label = @Translation("Styleguide snippet"),
 *   handlers = {
 *     "list_builder" = "Drupal\simple_styleguide\StyleguideSnippetListBuilder",
 *     "form" = {
 *       "add" = "Drupal\simple_styleguide\Form\StyleguideSnippetForm",
 *       "edit" = "Drupal\simple_styleguide\Form\StyleguideSnippetForm",
 *       "delete" = "Drupal\simple_styleguide\Form\StyleguideSnippetDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\simple_styleguide\StyleguideSnippetHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "styleguide_snippet",
 *   admin_permission = "administer style guide",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/styleguide/snippets/{styleguide_snippet}",
 *     "add-form" = "/admin/config/styleguide/snippets/add",
 *     "edit-form" = "/admin/config/styleguide/snippets/{styleguide_snippet}/edit",
 *     "delete-form" = "/admin/config/styleguide/snippets/{styleguide_snippet}/delete",
 *     "collection" = "/admin/config/styleguide/snippets"
 *   }
 * )
 */
class StyleguideSnippet extends ConfigEntityBase implements StyleguideSnippetInterface {

  /**
   * The Styleguide snippet ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The styleguide snippet label.
   *
   * @var string
   */
  protected $label;

  /**
   * The styleguide snippet.
   *
   * @var string
   */
  public $snippet;

}
