<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\ContribArchivalInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContribArchivalController.
 *
 *  Returns responses for Contrib Archival routes.
 */
class ContribArchivalController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Contrib Archival revision.
   *
   * @param int $yabrm_contrib_archival_revision
   *   The Contrib Archival revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_contrib_archival_revision) {
    $yabrm_contrib_archival = $this->entityTypeManager()->getStorage('yabrm_contrib_archival')
      ->loadRevision($yabrm_contrib_archival_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_contrib_archival');

    return $view_builder->view($yabrm_contrib_archival);
  }

  /**
   * Page title callback for a Contrib Archival revision.
   *
   * @param int $yabrm_contrib_archival_revision
   *   The Contrib Archival revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_contrib_archival_revision) {
    $yabrm_contrib_archival = $this->entityTypeManager()->getStorage('yabrm_contrib_archival')
      ->loadRevision($yabrm_contrib_archival_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_contrib_archival->label(),
      '%date' => $this->dateFormatter->format($yabrm_contrib_archival->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Contrib Archival.
   *
   * @param \Drupal\yabrm\Entity\ContribArchivalInterface $yabrm_contrib_archival
   *   A Contrib Archival object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ContribArchivalInterface $yabrm_contrib_archival) {
    $account = $this->currentUser();
    $yabrm_contrib_archival_storage = $this->entityTypeManager()->getStorage('yabrm_contrib_archival');

    $langcode = $yabrm_contrib_archival->language()->getId();
    $langname = $yabrm_contrib_archival->language()->getName();
    $languages = $yabrm_contrib_archival->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ?
      $this->t('@langname revisions for %title', [
        '@langname' => $langname,
        '%title' => $yabrm_contrib_archival->label(),
      ]) :
        $this->t('Revisions for %title', [
          '%title' => $yabrm_contrib_archival->label(),
        ]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all contrib archival revisions") || $account->hasPermission('administer contrib archival entities')));
    $delete_permission = (($account->hasPermission("delete all contrib archival revisions") || $account->hasPermission('administer contrib archival entities')));

    $rows = [];

    $vids = $yabrm_contrib_archival_storage->revisionIds($yabrm_contrib_archival);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\Entity\ContribArchivalInterface $revision */
      $revision = $yabrm_contrib_archival_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_contrib_archival->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_contrib_archival.revision', [
            'yabrm_contrib_archival' => $yabrm_contrib_archival->id(),
            'yabrm_contrib_archival_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_contrib_archival->toLink($date)->toString();
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
              'url' => $has_translations ?
              Url::fromRoute('entity.yabrm_contrib_archival.translation_revert', [
                'yabrm_contrib_archival' => $yabrm_contrib_archival->id(),
                'yabrm_contrib_archival_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.yabrm_contrib_archival.revision_revert', [
                'yabrm_contrib_archival' => $yabrm_contrib_archival->id(),
                'yabrm_contrib_archival_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_contrib_archival.revision_delete', [
                'yabrm_contrib_archival' => $yabrm_contrib_archival->id(),
                'yabrm_contrib_archival_revision' => $vid,
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

    $build['yabrm_contrib_archival_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
