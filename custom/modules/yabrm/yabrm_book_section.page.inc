<?php

/**
 * @file
 * Contains yabrm_book_section.page.inc.
 *
 * Page callback for Book section reference entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Book section reference templates.
 *
 * Default template: yabrm_book_section.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_book_section(array &$variables) {
  // Fetch BookSectionReference Entity Object.
  $yabrm_book_section = $variables['elements']['#yabrm_book_section'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
