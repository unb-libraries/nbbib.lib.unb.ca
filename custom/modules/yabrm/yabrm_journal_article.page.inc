<?php

/**
 * @file
 * Contains yabrm_journal_article.page.inc.
 *
 * Page callback for Journal Article Reference entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Journal Article Reference templates.
 *
 * Default template: yabrm_journal_article.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_yabrm_journal_article(array &$variables) {
  // Fetch JournalArticleReference Entity Object.
  $yabrm_journal_article = $variables['elements']['#yabrm_journal_article'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
