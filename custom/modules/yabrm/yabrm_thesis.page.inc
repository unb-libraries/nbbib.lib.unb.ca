<?php

/**
 * @file
 * Contains yabrm_thesis.page.inc.
 *
 * Page callback for Thesis reference entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Thesis reference templates.
 *
 * Default template: yabrm_thesis.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_thesis(array &$variables) {
  // Fetch ThesisReference Entity Object.
  $yabrm_thesis = $variables['elements']['#yabrm_thesis'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
