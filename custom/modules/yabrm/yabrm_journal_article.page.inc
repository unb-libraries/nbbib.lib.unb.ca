<?php

/**
 * @file
 * Contains yabrm_journal_article.page.inc.
 *
 * Page callback for Journal Article Reference entities.
 */

use Drupal\Core\Render\Element;
use Drupal\yabrm\Entity\BibliographicContributor;
use Drupal\taxonomy\Entity\Term;

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

  // Extract and pass Entity ID.
  $eid = $yabrm_journal_article->id();
  $variables['content']['eid'] = $eid;

  // Pass reference type machine name.
  $variables['content']['type_name'] = 'yabrm_journal_article';

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
        isset($paragraph->get('field_yabrm_contributor_role')->getValue()[0]['target_id']) ?
        $paragraph->get('field_yabrm_contributor_role')->getValue()[0]['target_id'] : NULL;

      $role = !empty(Term::load($rid)) ? Term::load($rid)->getName() : NULL;

      // Contributor ID and names.
      $cid =
        isset($paragraph->get('field_yabrm_contributor_person')->getValue()[0]['target_id']) ?
        $paragraph->get('field_yabrm_contributor_person')->getValue()[0]['target_id'] : NULL;

      $contrib = BibliographicContributor::load($cid);

      if (!empty($contrib)) {
        $c_name = isset($contrib->get('name')->getValue()[0]['value']) ?
          $contrib->get('name')->getValue()[0]['value'] : NULL;

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

        $display_name = isset($c_dname) ? $c_dname : NULL;

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
    empty($yabrm_journal_article->get('publication_year')->getValue()[0]['value']) ? NULL : $yabrm_journal_article->get('publication_year')->getValue()[0]['value'];
  $variables['content']['pub_year'] = $pub_year;

  // Process languages.
  // Process languages.
  $lang_codes = [
    'eng' => 'English',
    'fre' => 'French',
    'deu' => 'German',
    'mic' => 'Mi’kmaq/Mi’kmaw/Micmac',
    'spa' => 'Spanish',
    'pqm' => 'Wolastoqey-Passamaquoddy',
  ];

  $i = 0;

  while (!empty($variables['content']['language'][$i])) {
    $lang_key = $variables['content']['language'][$i]['#markup'];
    $variables['content']['lang_names'][] = $lang_codes[$lang_key];
    $i++;
  }

  // Build citation elements.
  $authors = empty($variables['content']['authors']) ? NULL
    : $variables['content']['authors'];
  $editors = empty($variables['content']['editors']) ? NULL
    : $variables['content']['editors'];
  $place = empty($variables['content']['place']['0']['#context']['value']) ? NULL
    : $variables['content']['place']['0']['#context']['value'];
  $publication_year =
    empty($variables['content']['publication_year']['0']['#markup']) ? NULL
    : $variables['content']['publication_year']['0']['#markup'];
  $publication_month =
    empty($variables['content']['publication_month']['0']['#markup']) ? NULL
    : $variables['content']['publication_month']['0']['#markup'];
  $publication_day =
    empty($variables['content']['publication_day']['0']['#markup']) ? NULL
    : $variables['content']['publication_day']['0']['#markup'];
  $title =
    empty($variables['content']['title']['0']['#context']['value']) ? NULL
    : $variables['content']['title']['0']['#context']['value'];
  $publisher =
    empty($variables['content']['publisher']['0']['#context']['value']) ? NULL
    : $variables['content']['publisher']['0']['#context']['value'];
  $notes = empty($variables['content']['notes']['0']['#text']) ? NULL
    : $variables['content']['notes']['0']['#text'];
  $url = empty($yabrm_journal_article->get('url')->getValue()[0]['uri']) ? NULL
    : $yabrm_journal_article->get('url')->getValue()[0]['uri'];

  $title_wad = '';
  $contribs_wad = '';
  $pub_wad = '';

  // URL alias.
  $variables['content']['url_alias'] = $yabrm_journal_article->url();

  // Title.
  if ($title) {
    $title_wad .= $title;
    // Only add period if last character is not punctuation.
    if (!preg_match("/[.!?,;:]$/", $title_wad)) {
      $title_wad .= '.';
    }
    $variables['content']['title_wad'] = $title_wad;
  }

  // Contributors.
  if ($authors) {
    $contribs_wad = journal_article_contribs_wad($authors);
  }
  elseif ($editors) {
    $contribs_wad = journal_article_contribs_wad($editors);
  }

  $variables['content']['contribs_wad'] = $contribs_wad;

  // Publishing.
  if ($place) {
    $pub_wad .= $place;
  }
  // Only add colon if there's publisher or publication year.
  if ($place && ($publisher || $publication_year)) {
    $pub_wad .= ':';
  }

  if ($publisher) {
    $pub_wad .= ' ' . $publisher;
    if ($publication_year) {
      $pub_wad .= ',';
    }
    else {
      $pub_wad .= '.';
    }
  }

  if ($publication_year) {
    $months = ['Jan.', 'Feb.', 'Mar.', 'Apr.', 'May.', 'Jun.', 'Jul.', 'Aug.',
      'Sep.', 'Oct.', 'Nov.', 'Dec.',
    ];
    // Only add day if month is present.
    if ($publication_month) {
      if ($publication_day) {
        $pub_wad .= ' ' . $publication_day;
      }
      // Month number converted to 3-character name.
      $pub_wad .= ' ' . $months[(int) $publication_month - 1];
    }
    $pub_wad .= ' ' . $publication_year . '.';
  }

  $variables['content']['pub_wad'] = $pub_wad;

  // Notes.
  if ($notes) {
    $notes_wad = ' ' . $notes;
    $notes_wad .= !preg_match("/[.!?,;:]$/", $notes) ? '.' : '';
    $variables['content']['notes_wad'] = $notes_wad;
  }

  // URL.
  if ($url) {
    $url_wad = ' ' . $url;
    $variables['content']['url_wad'] = $url_wad;
  }
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
function journal_article_contribs_wad(array $contributors) {
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
