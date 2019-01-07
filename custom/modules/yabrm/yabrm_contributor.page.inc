<?php

/**
 * @file
 * Contains yabrm_contributor.page.inc.
 *
 * Page callback for Bibliographic Contributor entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bibliographic Contributor templates.
 *
 * Default template: yabrm_contributor.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_contributor(array &$variables) {
  // Fetch BibliographicContributor Entity Object.
  $yabrm_contributor = $variables['elements']['#yabrm_contributor'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
