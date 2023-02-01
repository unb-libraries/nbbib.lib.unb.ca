<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BibliographicContributorArchivalInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Render\Renderer;

/**
 * Class BibliographicContributorArchivalController.
 *
 *  Returns responses for Bibliographic Contributor Archival routes.
 */
class BibliographicContributorArchivalController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * For services dependency injection.
   *
   * @var Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * For services dependency injection.
   *
   * @var Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Datetime\DateFormatter $date_formatter
   *   For services dependency injection.
   * @param Drupal\Core\Render\Renderer $renderer
   *   For services dependency injection.
   */
  public function __construct(
    DateFormatter $date_formatter,
    Renderer $renderer) {
    $this->dateFormatter = $date_formatter;
    $this->renderer = $renderer;
  }

  /**
   * Object create method.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   *
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('date.formatter'),
      $container->get('renderer')
    );
  }

  /**
   * Displays a Bibliographic Contributor Archival  revision.
   *
   * @param int $yabrm_contributor_archival_revision
   *   The Bibliographic Contributor Archival  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_contributor_archival_revision) {
    $yabrm_contributor_archival = $this->entityTypeManager()->getStorage('yabrm_contributor_archival')->loadRevision($yabrm_contributor_archival_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_contributor_archival');

    return $view_builder->view($yabrm_contributor_archival);
  }

  /**
   * Page title callback for a Bibliographic Contributor Archival  revision.
   *
   * @param int $yabrm_contributor_archival_revision
   *   The Bibliographic Contributor Archival  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_contributor_archival_revision) {
    $yabrm_contributor_archival = $this->entityTypeManager()->getStorage('yabrm_contributor_archival')->loadRevision($yabrm_contributor_archival_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_contributor_archival->label(),
      '%date' => $this->dateFormatter->format($yabrm_contributor_archival->getRevisionCreationTime()),
    ]);
  }

  /**
   * Overview table of old revisions for Bibliographic Contributor Archival.
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorArchivalInterface $yabrm_contributor_archival
   *   A Bibliographic Contributor Archival  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BibliographicContributorArchivalInterface $yabrm_contributor_archival) {
    $account = $this->currentUser();
    $langcode = $yabrm_contributor_archival->language()->getId();
    $langname = $yabrm_contributor_archival->language()->getName();
    $languages = $yabrm_contributor_archival->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_contributor_archival_storage = $this->entityTypeManager()->getStorage('yabrm_contributor_archival');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_contributor_archival->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_contributor_archival->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all bibliographic contributor archival revisions") || $account->hasPermission('administer bibliographic contributor archival entities')));
    $delete_permission = (($account->hasPermission("delete all bibliographic contributor archival revisions") || $account->hasPermission('administer bibliographic contributor archival entities')));

    $rows = [];

    $vids = $yabrm_contributor_archival_storage->revisionIds($yabrm_contributor_archival);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BibliographicContributorArchivalInterface $revision */
      $revision = $yabrm_contributor_archival_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_contributor_archival->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_contributor_archival.revision', [
            'yabrm_contributor_archival' => $yabrm_contributor_archival->id(),
            'yabrm_contributor_archival_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_contributor_archival->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
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
              'url' => Url::fromRoute('entity.yabrm_contributor_archival.revision_revert', [
                'yabrm_contributor_archival' => $yabrm_contributor_archival->id(),
                'yabrm_contributor_archival_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_contributor_archival.revision_delete', [
                'yabrm_contributor_archival' => $yabrm_contributor_archival->id(),
                'yabrm_contributor_archival_revision' => $vid,
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

    $build['yabrm_contributor_archival_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
