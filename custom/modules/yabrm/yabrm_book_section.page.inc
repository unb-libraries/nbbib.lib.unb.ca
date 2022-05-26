<?php

/**
 * @file
 * Contains yabrm_book_section.page.inc.
 *
 * Page callback for Book section reference entities.
 */

use Drupal\Core\Render\Element;
use Drupal\yabrm\Entity\BibliographicContributor;
use Drupal\taxonomy\Entity\Term;

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

  // Pass archives as plain names.
  $src_archives = $yabrm_book_section->get('archive')->getValue();
  $archives = [];

  foreach ($src_archives as $archive) {
    $term = Term::load($archive['target_id']);
    if ($term) {
      $archives[] = $term->getName();
    }
  }

  $variables['content']['archives'] = $archives;

  // Extract and pass Entity ID.
  $eid = $yabrm_book_section->id();
  $variables['content']['eid'] = $eid;

  // Pass reference type machine name.
  $variables['content']['type_name'] = 'yabrm_book_section';

  // Extract and pass author and editor names.
  // Get contributors.
  $contribs = $variables['content']['contributors'];
  $i = 0;

  while (!empty($contribs[$i])) {
    // Paragraph.
    $paragraph = !empty($contribs[$i]['#paragraph']) ?
      $contribs[$i]['#paragraph'] : NULL;

    if (!empty($paragraph)) {
      // Role ID and role.
      $rid =
        $paragraph->get('field_yabrm_contributor_role')->getValue()[0]['target_id'] ?? NULL;

      $role = !empty(Term::load($rid)) ? Term::load($rid)->getName() : NULL;

      // Contributor ID and names.
      $cid =
        $paragraph->get('field_yabrm_contributor_person')->getValue()[0]['target_id'] ?? NULL;

      $contrib = BibliographicContributor::load($cid);

      if (!empty($contrib)) {
        $c_name = $contrib->get('name')->getValue()[0]['value'] ?? NULL;

        $c_iname = $contrib->get('institution_name')->getValue()[0]['value'] ?? NULL;

        $c_fname = $contrib->get('first_name')->getValue()[0]['value'] ?? NULL;

        $c_lname = $contrib->get('last_name')->getValue()[0]['value'] ?? NULL;

        // If institution, pass as last name, otherwise pass name.
        $last_name = $c_iname ? $c_iname : $c_lname;
        $first_name = $c_fname;

        // Pass names for different roles.
        if (strtolower($role) == 'author') {
          $variables['content']['authors'][] = [
            'name' => $c_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
          ];
        }
        elseif (strtolower($role) == 'editor') {
          $variables['content']['editors'][] = [
            'name' => "$c_name (editor)",
            'first_name' => $first_name,
            'last_name' => $last_name,
          ];
        }

        // Pass all contributors roles and display names.
        $c_dname = $contrib->getName();

        $display_name = $c_dname ?? NULL;

        if (!empty($display_name)) {
          $variables['content']['contribs_all'][] = [
            'cid' => $cid,
            'display_name' => $display_name,
            'role' => $role,
          ];
        }
      }
    }
    $i++;
  }

  if (!empty($variables['content']['contribs_all'])) {
    array_multisort($variables['content']['contribs_all']);
  }

  // Sanitize and strong-type publication_year.
  $pub_year =
    empty($yabrm_book_section->get('publication_year')->getValue()[0]['value']) ? NULL : $yabrm_book_section->get('publication_year')->getValue()[0]['value'];
  $variables['content']['pub_year'] = $pub_year;

  // Process languages.
  $lang_codes = [
    'ara' => 'Arabic',
    'eng' => 'English',
    'fre' => 'French',
    'deu' => 'German',
    'mic' => 'Mi’kmaq/Mi’kmaw/Micmac',
    'spa' => 'Spanish',
    'pqm' => 'Wolastoqey/Maliseet',
  ];

  $i = 0;

  while (!empty($variables['content']['language'][$i])) {
    $lang_key = $variables['content']['language'][$i]['#markup'];
    $variables['content']['lang_names'][] = $lang_codes[$lang_key];
    $i++;
  }

  // Build citation elements.
  $authors = $variables['content']['authors'] ?? NULL;
  $editors = $variables['content']['editors'] ?? NULL;
  $place = $variables['content']['place']['0']['#context']['value'] ?? NULL;
  $publication_year = $variables['content']['publication_year']['0']['#markup']
    ?? NULL;
  $title = $variables['content']['title']['0']['#context']['value'] ?? NULL;
  $publisher = $variables['content']['publisher']['0']['#context']['value']
    ?? NULL;
  $notes = $variables['content']['notes']['0']['#text'] ?? NULL;

  // Specific to Book Section.
  $publication_title =
    $variables['content']['publication_title']['0']['#context']['value']
    ?? NULL;
  $pages = $variables['content']['pages']['0']['#context']['value'] ?? NULL;

  // Prepare fragment containers.
  $title_wad = '';
  $contribs_wad = '';
  $pub_wad = '';

  // URL alias.
  $variables['content']['url_alias'] = $yabrm_book_section->toUrl()->toString();

  // Title.
  if ($title) {
    $title_wad .= trim($title);
    // Only add period if last character is not punctuation.
    if (!preg_match("/[.!?,;:]$/", $title_wad)) {
      $title_wad .= '.';
    }
    $variables['content']['title_wad'] = "\"$title_wad\"";
  }

  // Contributors.
  // If there are authors...
  if (!empty($authors)) {
    // Build formatted list.
    $au_names = array_column($authors, 'name');
    $au_wad = count($au_names) > 1 ?
      implode(', ', array_slice($au_names, 0, -1)) . ', and ' . end($au_names)
      : $au_names[0];
  }
  // If there are editors...
  if (!empty($editors)) {
    // Build formatted list.
    $ed_names = array_column($editors, 'name');
    $ed_wad = count($ed_names) > 1 ?
      implode(', ', array_slice($ed_names, 0, -1)) . ', and ' . end($ed_names)
      : $ed_names[0];
  }
  // If no authors...
  if (empty($authors)) {
    // But we have editors...
    if (!empty($editors)) {
      // Editors will take up the contributors segment.
      $contribs_wad = $ed_wad;
    }
  }
  // If we have authors...
  else {
    // Authors go in the pre-title contributors segment.
    $contribs_wad = $au_wad;
    // If there are editors as well...
    if (!empty($editors)) {
      // Editors go in the post-title publication segment.
      $pub_wad .= "Edited by $ed_wad";
      // Only add period if last character is not punctuation.
      if (!preg_match("/[.!?,;:]$/", $pub_wad)) {
        $pub_wad .= '.';
      }
    }
  }

  // Only add period if last character is not punctuation.
  if (!preg_match("/[.!?,;:]$/", $contribs_wad)) {
    $contribs_wad .= '.';
  }

  $variables['content']['contribs_wad'] = $contribs_wad;

  // Publication.
  if ($publication_title) {
    $pub_wad .= ' <em>' . trim($publication_title) . '</em>';
  }

  if ($place) {
    $pub_wad .= '. ' . trim($place);

    if ($publisher) {
      $pub_wad .= ': ' . trim($publisher);
    }
  }
  else {
    if ($publisher) {
      $pub_wad .= '. ' . trim($publisher);
    }
  }

  if ($publication_year) {
    $pub_wad .= ', ' . trim($publication_year);
  }

  if ($pages) {
    $pub_wad .= ', pp. ' . trim($pages);
  }

  $variables['content']['pub_wad'] = $pub_wad . '. [book section]';
}

/**
 * Prepares contributors for passing to template.
 *
 * @param array $contributors
 *   An associative array containing:
 *   - name: Contributor display name.
 *   - first_name: Contributor first name.
 *   - last_name: Contributor last name.
 */
function book_section_contribs_wad(array $contributors) {
  $contribs_wad = '';
  $et_al = FALSE;

  // Only act if there's a name.
  if (!empty($contributors[0]['name'])) {
    // Content.
    $contribs_wad .= $contributors[0]['name'];
    // If there is a second.
    if (!empty($contributors[1]['name'])) {
      // If there is a third.
      if (!empty($contributors[2]['name'])) {
        $et_al = TRUE;
        $contribs_wad .= ', ';
      }
      else {
        $contribs_wad .= ' and ';
      }

      $contribs_wad .= $contributors[1]['name'];

      if ($et_al) {
        $contribs_wad .= ' et al';
      }
    }
  }

  // Only add period if last character is not punctuation.
  if (!preg_match("/[.!?,;:]$/", $contribs_wad)) {
    $contribs_wad .= '.';
  }

  return $contribs_wad;
}
