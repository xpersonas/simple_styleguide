<?php

namespace Drupal\simple_styleguide\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DefaultController.
 *
 * @package Drupal\simple_styleguide\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    $config = $this->config('simple_styleguide.styleguidesettings');
    $config_colors = $config->get('default_colors');
    $config_segments = $config->get('default_segments');

    // Selected segments.
    $default_segments = '';
    if ($config_segments) {
      foreach ($config_segments as $key => $value) {
        if ($value) {
          $default_segments[] = $key;
        }
      }
    }

    // Custom colors.
    $default_colors = '';
    if ($config_colors) {
      foreach ($config_colors as $key => $value) {
        $color_split = explode('|', $value);

        if (!empty($color_split[0])) {
          $default_colors[$key]['name'] = $color_split[0];
        }

        if (!empty($color_split[1])) {
          $default_colors[$key]['hex'] = $color_split[1];
        }

        if (!empty($color_split[2])) {
          $default_colors[$key]['description'] = $color_split[2];
        }
      }
    }

    // Custom segments.
    $custom_segments = '';
    $storage = $this->entityTypeManager->getStorage('styleguide_segment');
    $ids = $storage->getQuery()->execute();
    if (!empty($ids)) {
      $custom_segments = $storage->loadMultiple($ids);
    }

    return array(
      '#theme' => 'simple_styleguide',
      '#default_segments' => $default_segments,
      '#default_colors' => $default_colors,
      '#custom_segments' => $custom_segments,
    );
  }

}
