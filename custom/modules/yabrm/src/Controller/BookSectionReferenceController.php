<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BookSectionReferenceInterface;

/**
 * Class BookSectionReferenceController.
 *
 *  Returns responses for Book Section reference routes.
 */
class BookSectionReferenceController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Book Section reference revision.
   *
   * @param int $yabrm_book_section_revision
   *   The Book Section reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_book_section_revision) {
    $yabrm_book_section = $this->entityManager()->getStorage('yabrm_book_section')->loadRevision($yabrm_book_section_revision);
    $view_builder = $this->entityManager()->getViewBuilder('yabrm_book_section');
    return $view_builder->view($yabrm_book_section);
  }

  /**
   * Page title callback for a Book Section reference  revision.
   *
   * @param int $yabrm_book_section_revision
   *   The BookSection reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_book_section_revision) {
    $yabrm_book_section = $this->entityManager()->getStorage('yabrm_book_section')->loadRevision($yabrm_book_section_revision);
    return $this->t('Revision of %title from %date', ['%title' => $yabrm_book_section->label(), '%date' => format_date($yabrm_book_section->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Book Section reference .
   *
   * @param \Drupal\yabrm\Entity\BookSectionReferenceInterface $yabrm_book_section
   *   A Book Section reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BookSectionReferenceInterface $yabrm_book_section) {
    $account = $this->currentUser();
    $langcode = $yabrm_book_section->language()->getId();
    $langname = $yabrm_book_section->language()->getName();
    $languages = $yabrm_book_section->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_book_section_storage = $this->entityManager()->getStorage('yabrm_book_section');
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $yabrm_book_section->label()]) : $this->t('Revisions for %title', ['%title' => $yabrm_book_section->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all book section reference revisions") || $account->hasPermission('administer book section reference entities')));
    $delete_permission = (($account->hasPermission("delete all book section reference revisions") || $account->hasPermission('administer book section reference entities')));
    $rows = [];
    $vids = $yabrm_book_section_storage->revisionIds($yabrm_book_section);
    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BookSectionReferenceInterface $revision */
      $revision = $yabrm_book_section_storage->loadRevision($vid);

      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');

        if ($vid != $yabrm_book_section->getRevisionId()) {
          $link = $this->l($date, new Url('entity.yabrm_book_section.revision', ['yabrm_book_section' => $yabrm_book_section->id(), 'yabrm_book_section_revision' => $vid]));
        }
        else {
          $link = $yabrm_book_section->link($date);
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
              'url' => Url::fromRoute('entity.yabrm_book_section.revision_revert', ['yabrm_book_section' => $yabrm_book_section->id(), 'yabrm_book_section_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_book_section.revision_delete', ['yabrm_book_section' => $yabrm_book_section->id(), 'yabrm_book_section_revision' => $vid]),
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

    $build['yabrm_book_section_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];
    return $build;
  }

}
