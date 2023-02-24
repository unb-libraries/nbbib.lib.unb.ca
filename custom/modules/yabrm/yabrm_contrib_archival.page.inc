<?php

/**
 * @file
 * Contains yabrm_contrib_archival.page.inc.
 *
 * Page callback for Contrib Archival entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Contrib Archival templates.
 *
 * Default template: yabrm_contrib_archival.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_contrib_archival(array &$variables) {
  // Fetch ContribArchival Entity Object.
  $yabrm_contrib_archival = $variables['elements']['#yabrm_contrib_archival'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
