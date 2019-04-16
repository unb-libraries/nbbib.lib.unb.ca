<?php

/**
 * @file
 * Contains yabrm_book.page.inc.
 *
 * Page callback for Book reference entities.
 */

use Drupal\Core\Render\Element;
use Drupal\yabrm\Entity\BibliographicContributor;
use Drupal\taxonomy\Entity\Term;

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

  // Extract and pass author and editor names.
  // Get contributors.
  $contribs = $variables['content']['contributors'];
  $i = 0;

  while (!empty($contribs[$i]['#paragraph'])) {

    // Paragraph.
    $paragraph = $contribs[$i];

    // Role ID and role.
    $rid =
      isset($paragraph->get('field_yabrm_contributor_role')->getValue()[0]['target_id']) ?
      $paragraph->get('field_yabrm_contributor_role')->getValue()[0]['target_id'] : NULL;

    $role = Term::load($rid)->getName();

    // Contributor ID and names.
    $cid =
      isset($paragraph->get('field_yabrm_contributor_person')->getValue()[0]['target_id']) ?
      $paragraph->get('field_yabrm_contributor_person')->getValue()[0]['target_id'] : NULL;

    $contrib = BibliographicContributor::load($cid);

    $c_iname = isset($contrib->get('institution_name')->getValue()[0]['value']) ?
      $contrib->get('institution_name')->getValue()[0]['value'] : NULL;

    $c_fname = isset($contrib->get('first_name')->getValue()[0]['value']) ?
      $contrib->get('first_name')->getValue()[0]['value'] : NULL;

    $c_lname = isset($contrib->get('last_name')->getValue()[0]['value']) ?
      $contrib->get('last_name')->getValue()[0]['value'] : NULL;

    // If institution, pass as last name, otherwise pass name.
    $last_name = $c_iname ? $c_iname : $c_lname;
    $first_name = $c_fname;

    // Pass names for different roles.
    if (strtolower($role) == 'author') {
      $variables['content']['authors'][] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
      ];
    }
    elseif (strtolower($role) == 'editor') {
      $variables['content']['editors'][] = [
        'first_name' => $first_name,
        'last_name' => $last_name,
      ];
    }

    $i++;
  }

}
