<?php

/**
 * @file
 * Contains yabrm_biblio_reference.page.inc.
 *
 * Page callback for Bibliographic Reference entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bibliographic Reference templates.
 *
 * Default template: yabrm_biblio_reference.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_biblio_reference(array &$variables) {
  // Fetch BibliographicReference Entity Object.
  $yabrm_biblio_reference = $variables['elements']['#yabrm_biblio_reference'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
