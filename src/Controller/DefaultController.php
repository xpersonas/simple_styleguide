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
    $storage = $this->entityTypeManager->getStorage('styleguide_segment');

    $ids = $storage->getQuery()
      ->execute();

    $segments = $storage->loadMultiple($ids);

    return array(
      '#theme' => 'simple_styleguide',
      '#segments' => $segments,
    );
  }

}
