<?php

/**
 * @file
 * Contains yabrm_book.page.inc.
 *
 * Page callback for Book reference entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Book reference templates.
 *
 * Default template: yabrm_book.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_book(array &$variables) {
  // Fetch BookReference Entity Object.
  $yabrm_book = $variables['elements']['#yabrm_book'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }

  $variables['content']['authors'] = [
    ['first_name' => 'First', 'last_name' => 'Last'],
    ['first_name' => 'First2', 'last_name' => 'Last2'],
  ];
}
