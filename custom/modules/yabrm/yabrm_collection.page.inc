<?php

/**
 * @file
 * Contains yabrm_collection.page.inc.
 *
 * Page callback for Bibliographic Collection entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bibliographic Collection templates.
 *
 * Default template: yabrm_collection.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_collection(array &$variables) {
  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
