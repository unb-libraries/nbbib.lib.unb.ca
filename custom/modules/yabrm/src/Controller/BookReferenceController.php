<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\EntityInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BookReferenceInterface;

/**
 * Class BookReferenceController.
 *
 *  Returns responses for Book reference routes.
 */
class BookReferenceController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Book reference  revision.
   *
   * @param int $yabrm_book_revision
   *   The Book reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_book_revision) {
    $yabrm_book = $this->entityTypeManager()->getStorage('yabrm_book')->loadRevision($yabrm_book_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_book');

    return $view_builder->view($yabrm_book);
  }

  /**
   * Page title callback for a Book reference  revision.
   *
   * @param int $yabrm_book_revision
   *   The Book reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_book_revision) {
    $yabrm_book = $this->entityTypeManager()->getStorage('yabrm_book')->loadRevision($yabrm_book_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_book->label(),
      '%date' => \Drupal::service('date.formatter')->format($yabrm_book->getRevisionCreationTime())
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Book reference .
   *
   * @param \Drupal\yabrm\Entity\BookReferenceInterface $yabrm_book
   *   A Book reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BookReferenceInterface $yabrm_book) {
    $account = $this->currentUser();
    $langcode = $yabrm_book->language()->getId();
    $langname = $yabrm_book->language()->getName();
    $languages = $yabrm_book->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_book_storage = $this->entityTypeManager()->getStorage('yabrm_book');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_book->label()
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_book->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all book reference revisions") || $account->hasPermission('administer book reference entities')));
    $delete_permission = (($account->hasPermission("delete all book reference revisions") || $account->hasPermission('administer book reference entities')));

    $rows = [];

    $vids = $yabrm_book_storage->revisionIds($yabrm_book);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BookReferenceInterface $revision */
      $revision = $yabrm_book_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_book->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_book.revision', [
            'yabrm_book' => $yabrm_book->id(),
            'yabrm_book_revision' => $vid
          ]));
        }
        else {
          $link = EntityInterface::toLink()->toString($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList()
              ],
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
              'url' => Url::fromRoute('entity.yabrm_book.revision_revert', [
                'yabrm_book' => $yabrm_book->id(),
                'yabrm_book_revision' => $vid
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_book.revision_delete', [
                'yabrm_book' => $yabrm_book->id(),
                'yabrm_book_revision' => $vid
              ]),
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

    $build['yabrm_book_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
