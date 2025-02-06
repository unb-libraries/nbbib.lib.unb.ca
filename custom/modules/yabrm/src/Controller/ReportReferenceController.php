<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\ReportReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ReportReferenceController.
 *
 *  Returns responses for Report reference routes.
 */
class ReportReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Report reference  revision.
   *
   * @param int $yabrm_report_revision
   *   The Report reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_report_revision) {
    $yabrm_report = $this->entityTypeManager()->getStorage('yabrm_report')->loadRevision($yabrm_report_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_report');

    return $view_builder->view($yabrm_report);
  }

  /**
   * Page title callback for a Report reference  revision.
   *
   * @param int $yabrm_report_revision
   *   The Report reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_report_revision) {
    $yabrm_report = $this->entityTypeManager()->getStorage('yabrm_report')->loadRevision($yabrm_report_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_report->label(),
      '%date' => $this->dateFormatter->format($yabrm_report->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Report reference .
   *
   * @param \Drupal\yabrm\Entity\ReportReferenceInterface $yabrm_report
   *   A Report reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ReportReferenceInterface $yabrm_report) {
    $account = $this->currentUser();
    $langcode = $yabrm_report->language()->getId();
    $langname = $yabrm_report->language()->getName();
    $languages = $yabrm_report->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_report_storage = $this->entityTypeManager()->getStorage('yabrm_report');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_report->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_report->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all report reference revisions") || $account->hasPermission('administer report reference entities')));
    $delete_permission = (($account->hasPermission("delete all report reference revisions") || $account->hasPermission('administer report reference entities')));

    $rows = [];

    $vids = $yabrm_report_storage->revisionIds($yabrm_report);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\ReportReferenceInterface $revision */
      $revision = $yabrm_report_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_report->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_report.revision', [
            'yabrm_report' => $yabrm_report->id(),
            'yabrm_report_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_report->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_report.revision_revert', [
                'yabrm_report' => $yabrm_report->id(),
                'yabrm_report_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_report.revision_delete', [
                'yabrm_report' => $yabrm_report->id(),
                'yabrm_report_revision' => $vid,
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

    $build['yabrm_report_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
