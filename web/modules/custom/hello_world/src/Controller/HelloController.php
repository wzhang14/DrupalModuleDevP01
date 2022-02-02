<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Returns responses for Hello World routes.
 */
class HelloController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function helloWorld() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello World!'),
    ];

    return $build;
  }

  /**
   * Greets a person.
   * 
   * @param [type] $name
   * @return void
   */
  public function hello($name, $nid = NULL) {
    $output = $this->t('Hello, @name!', ['@name' => $name]);

    if (!is_null($nid) && is_numeric($nid)) {
      $node = Node::load($nid);
      ksm($node);
      if ($node) {
        $title = $node->getTitle();
        $url = Url::fromRoute('entity.node.canonical', ['node' => $nid], ['attributes' => ["target" => "_blank"]]);
        $internal_link = Link::fromTextAndUrl($title, $url)->toString();
        ksm($url->toString());
        $output = $this->t('Hello, @name, this is the node\'s link, @link.', ['@name' => $name, '@link' => $internal_link]);
      }
      else {
        $output = $this->t('Hello, @name, this is no node with id, @id.', ['@name' => $name, '@id' => $nid]);
      }
    }
    

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $output,
    ];

    return $build;
  }

  /**
   * Returns page title.
   */
  public function title($name) {
    
    return $this->t('Hello, @name!', ['@name' => $name]);
  }

}
