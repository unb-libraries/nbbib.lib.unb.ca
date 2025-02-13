<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\PeriodicalReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PeriodicalReferenceController.
 *
 *  Returns responses for Periodical reference routes.
 */
class PeriodicalReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Periodical reference  revision.
   *
   * @param int $yabrm_periodical_revision
   *   The Periodical reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_periodical_revision) {
    $yabrm_periodical = $this->entityTypeManager()->getStorage('yabrm_periodical')->loadRevision($yabrm_periodical_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_periodical');

    return $view_builder->view($yabrm_periodical);
  }

  /**
   * Page title callback for a Periodical reference  revision.
   *
   * @param int $yabrm_periodical_revision
   *   The Periodical reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_periodical_revision) {
    $yabrm_periodical = $this->entityTypeManager()->getStorage('yabrm_periodical')->loadRevision($yabrm_periodical_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_periodical->label(),
      '%date' => $this->dateFormatter->format($yabrm_periodical->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Periodical reference .
   *
   * @param \Drupal\yabrm\Entity\PeriodicalReferenceInterface $yabrm_periodical
   *   A Periodical reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(PeriodicalReferenceInterface $yabrm_periodical) {
    $account = $this->currentUser();
    $langcode = $yabrm_periodical->language()->getId();
    $langname = $yabrm_periodical->language()->getName();
    $languages = $yabrm_periodical->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_periodical_storage = $this->entityTypeManager()->getStorage('yabrm_periodical');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_periodical->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_periodical->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all periodical reference revisions") || $account->hasPermission('administer periodical reference entities')));
    $delete_permission = (($account->hasPermission("delete all periodical reference revisions") || $account->hasPermission('administer periodical reference entities')));

    $rows = [];

    $vids = $yabrm_periodical_storage->revisionIds($yabrm_periodical);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\PeriodicalReferenceInterface $revision */
      $revision = $yabrm_periodical_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_periodical->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_periodical.revision', [
            'yabrm_periodical' => $yabrm_periodical->id(),
            'yabrm_periodical_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_periodical->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_periodical.revision_revert', [
                'yabrm_periodical' => $yabrm_periodical->id(),
                'yabrm_periodical_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_periodical.revision_delete', [
                'yabrm_periodical' => $yabrm_periodical->id(),
                'yabrm_periodical_revision' => $vid,
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

    $build['yabrm_periodical_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
