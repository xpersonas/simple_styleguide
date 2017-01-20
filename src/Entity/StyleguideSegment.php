<?php

namespace Drupal\simple_styleguide\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Styleguide segment entity.
 *
 * @ConfigEntityType(
 *   id = "styleguide_segment",
 *   label = @Translation("Styleguide segment"),
 *   handlers = {
 *     "list_builder" = "Drupal\simple_styleguide\StyleguideSegmentListBuilder",
 *     "form" = {
 *       "add" = "Drupal\simple_styleguide\Form\StyleguideSegmentForm",
 *       "edit" = "Drupal\simple_styleguide\Form\StyleguideSegmentForm",
 *       "delete" = "Drupal\simple_styleguide\Form\StyleguideSegmentDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\simple_styleguide\StyleguideSegmentHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "styleguide_segment",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/styleguide/segments/{styleguide_segment}",
 *     "add-form" = "/admin/config/styleguide/segments/add",
 *     "edit-form" = "/admin/config/styleguide/segments/{styleguide_segment}/edit",
 *     "delete-form" = "/admin/config/styleguide/segments/{styleguide_segment}/delete",
 *     "collection" = "/admin/config/styleguide/segments"
 *   }
 * )
 */
class StyleguideSegment extends ConfigEntityBase implements StyleguideSegmentInterface {

  /**
   * The Styleguide segment ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The styleguide segment label.
   *
   * @var string
   */
  protected $label;

  /**
   * @param string $label
   */
  public function setLabel($label) {
    $this->label = $label;
  }

  /**
   * The styleguide segment.
   *
   * @var string
   */
  public $segment;

}
