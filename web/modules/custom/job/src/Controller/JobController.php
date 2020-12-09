<?php

namespace Drupal\job\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for Job routes.
 */
class JobController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build($id, $published) {
    $node = Node::load($id);
    $node->field_published->value = $published;
    $node->save();
    return new Response($node->id());

  }

}
