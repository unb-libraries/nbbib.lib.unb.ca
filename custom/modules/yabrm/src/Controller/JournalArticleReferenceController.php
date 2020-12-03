<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\JournalArticleReferenceInterface;

/**
 * Class JournalArticleReferenceController.
 *
 *  Returns responses for Journal Article Reference routes.
 */
class JournalArticleReferenceController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Journal Article Reference  revision.
   *
   * @param int $yabrm_journal_article_revision
   *   The Journal Article Reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_journal_article_revision) {
    $yabrm_journal_article = $this->entityTypeManager()->getStorage('yabrm_journal_article')->loadRevision($yabrm_journal_article_revision);
    $view_builder = $this->entityManager()->getViewBuilder('yabrm_journal_article');

    return $view_builder->view($yabrm_journal_article);
  }

  /**
   * Page title callback for a Journal Article Reference  revision.
   *
   * @param int $yabrm_journal_article_revision
   *   The Journal Article Reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_journal_article_revision) {
    $yabrm_journal_article = $this->entityTypeManager()->getStorage('yabrm_journal_article')->loadRevision($yabrm_journal_article_revision);
    return $this->t('Revision of %title from %date', ['%title' => $yabrm_journal_article->label(), '%date' => \Drupal::service('date.formatter')->format($yabrm_journal_article->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Journal Article Reference .
   *
   * @param \Drupal\yabrm\Entity\JournalArticleReferenceInterface $yabrm_journal_article
   *   A Journal Article Reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(JournalArticleReferenceInterface $yabrm_journal_article) {
    $account = $this->currentUser();
    $langcode = $yabrm_journal_article->language()->getId();
    $langname = $yabrm_journal_article->language()->getName();
    $languages = $yabrm_journal_article->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_journal_article_storage = $this->entityTypeManager()->getStorage('yabrm_journal_article');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $yabrm_journal_article->label()]) : $this->t('Revisions for %title', ['%title' => $yabrm_journal_article->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all journal article reference revisions") || $account->hasPermission('administer journal article reference entities')));
    $delete_permission = (($account->hasPermission("delete all journal article reference revisions") || $account->hasPermission('administer journal article reference entities')));

    $rows = [];

    $vids = $yabrm_journal_article_storage->revisionIds($yabrm_journal_article);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\JournalArticleReferenceInterface $revision */
      $revision = $yabrm_journal_article_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_journal_article->getRevisionId()) {
          $link = $this->l($date, new Url('entity.yabrm_journal_article.revision', ['yabrm_journal_article' => $yabrm_journal_article->id(), 'yabrm_journal_article_revision' => $vid]));
        }
        else {
          $link = $yabrm_journal_article->link($date);
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
              'url' => Url::fromRoute('entity.yabrm_journal_article.revision_revert', ['yabrm_journal_article' => $yabrm_journal_article->id(), 'yabrm_journal_article_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_journal_article.revision_delete', ['yabrm_journal_article' => $yabrm_journal_article->id(), 'yabrm_journal_article_revision' => $vid]),
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

    $build['yabrm_journal_article_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
