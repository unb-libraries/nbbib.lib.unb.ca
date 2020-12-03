<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BibliographicCollectionInterface;

/**
 * Class BibliographicCollectionController.
 *
 *  Returns responses for Bibliographic Collection routes.
 */
class BibliographicCollectionController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Bibliographic Collection  revision.
   *
   * @param int $yabrm_collection_revision
   *   The Bibliographic Collection  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_collection_revision) {
    $yabrm_collection = $this->entityTypeManager()->getStorage('yabrm_collection')->loadRevision($yabrm_collection_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_collection');

    return $view_builder->view($yabrm_collection);
  }

  /**
   * Page title callback for a Bibliographic Collection  revision.
   *
   * @param int $yabrm_collection_revision
   *   The Bibliographic Collection  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_collection_revision) {
    $yabrm_collection = $this->entityTypeManager()->getStorage('yabrm_collection')->loadRevision($yabrm_collection_revision);
    return $this->t('Revision of %title from %date', ['%title' => $yabrm_collection->label(), '%date' => \Drupal::service('date.formatter')->format($yabrm_collection->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Bibliographic Collection .
   *
   * @param \Drupal\yabrm\Entity\BibliographicCollectionInterface $yabrm_collection
   *   A Bibliographic Collection  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BibliographicCollectionInterface $yabrm_collection) {
    $account = $this->currentUser();
    $langcode = $yabrm_collection->language()->getId();
    $langname = $yabrm_collection->language()->getName();
    $languages = $yabrm_collection->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_collection_storage = $this->entityTypeManager()->getStorage('yabrm_collection');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $yabrm_collection->label()]) : $this->t('Revisions for %title', ['%title' => $yabrm_collection->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all bibliographic collection revisions") || $account->hasPermission('administer bibliographic collection entities')));
    $delete_permission = (($account->hasPermission("delete all bibliographic collection revisions") || $account->hasPermission('administer bibliographic collection entities')));

    $rows = [];

    $vids = $yabrm_collection_storage->revisionIds($yabrm_collection);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BibliographicCollectionInterface $revision */
      $revision = $yabrm_collection_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_collection->getRevisionId()) {
          $link = $this->l($date, new Url('entity.yabrm_collection.revision', ['yabrm_collection' => $yabrm_collection->id(), 'yabrm_collection_revision' => $vid]));
        }
        else {
          $link = $yabrm_collection->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('entity.yabrm_collection.revision_revert', ['yabrm_collection' => $yabrm_collection->id(), 'yabrm_collection_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_collection.revision_delete', ['yabrm_collection' => $yabrm_collection->id(), 'yabrm_collection_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['yabrm_collection_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
