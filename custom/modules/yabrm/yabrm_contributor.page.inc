<?php

/**
 * @file
 * Contains yabrm_contributor.page.inc.
 *
 * Page callback for Bibliographic Contributor entities.
 */

use Drupal\Core\Render\Element;
use Drupal\file\Entity\File;
use Drupal\yabrm\Entity\ContribArchival;

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

  // Extract and pass Entity ID.
  $id = $yabrm_contributor->id();
  $variables['content']['id'] = $id;  

  // Load reference cover if no contributor photo.
  $entity = $yabrm_contributor;

  // If no picture added to contributor, use reference picture if available.
  $picture = $entity->getPicture();
  $img_item = NULL;

  if (empty($picture['target_id'])) {
    $cid = $entity->id();

    $pids = \Drupal::entityQuery('paragraph')
      ->condition('type', 'yabrm_bibliographic_contributor')
      ->condition('field_yabrm_contributor_person', $cid)
      ->accessCheck(FALSE)
      ->execute();
    
    // Load reference types from configuration.
    $config = \Drupal::config('nbbib_core.settings');
    $types = $config->get('nbbib_types');
    $types = $types ? array_column($types, 'entity') : NULL;

    // Iterate through contributor paragraph ids.
    foreach ($pids as $pid) {

      // Iterate through reference types.
      foreach ($types as $type) {
        $entity_storage = \Drupal::entityTypeManager()->getStorage($type);

        // Query for matching references.
        $rids = \Drupal::entityQuery($type)
        ->condition('contributors', $pid, 'IN')
        ->condition('status', 1)
        ->accessCheck(FALSE)
        ->execute();

        // Iterate through matching references.
        foreach ($rids as $rid) {
          // Get reference cover image.
          $reference = $entity_storage->load($rid);

          if ($reference) {
            $img_item = $reference->get('cover_image')->getIterator()[0];
          }
          // Escape all 3 loops if image found.
          if ($img_item->getValue()['target_id'] != 0) {
            break 3;
          }
        }
      }
    }

    if ($img_item and ($img_item->getValue()['target_id'] != 0)) {
      $iid = $img_item->get('target_id')->getValue();
      $file = File::load($iid);

      $variables['content']['cover'] = [
        '#theme' => 'image',
        '#uri' => $file->getFileUri(),
        '#width' => '300',
        '#height' => '400',
        '#style' => 'contrib_photo',
      ];
    }
  }

  // Add views for all archival references to $content.
  $archivals = $yabrm_contributor->archival->getValue();
  $view_builder = \Drupal::entityTypeManager()
    ->getViewBuilder('yabrm_contrib_archival');

  foreach ($archivals as $archival) {
    $entity = ContribArchival::load($archival['target_id']);
    $view = $view_builder->view($entity, 'contributor_embed');
    $variables['content']['full_archivals'][] = $view;
  }
}
