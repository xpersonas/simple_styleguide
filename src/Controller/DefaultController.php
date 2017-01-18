<?php

namespace Drupal\simple_styleguide\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 *
 * @package Drupal\simple_styleguide\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function index() {
    return array(
      '#theme' => 'simple_styleguide',
    );
  }

}
