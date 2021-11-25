<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\ThesisReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\Render\Renderer;

/**
 * Class ThesisReferenceController.
 *
 *  Returns responses for Thesis reference routes.
 */
class ThesisReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Thesis reference  revision.
   *
   * @param int $yabrm_thesis_revision
   *   The Thesis reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_thesis_revision) {
    $yabrm_thesis = $this->entityTypeManager()->getStorage('yabrm_thesis')->loadRevision($yabrm_thesis_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_thesis');

    return $view_builder->view($yabrm_thesis);
  }

  /**
   * Page title callback for a Thesis reference  revision.
   *
   * @param int $yabrm_thesis_revision
   *   The Thesis reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_thesis_revision) {
    $yabrm_thesis = $this->entityTypeManager()->getStorage('yabrm_thesis')->loadRevision($yabrm_thesis_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_thesis->label(),
      '%date' => $this->dateFormatter->format($yabrm_thesis->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Thesis reference .
   *
   * @param \Drupal\yabrm\Entity\ThesisReferenceInterface $yabrm_thesis
   *   A Thesis reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ThesisReferenceInterface $yabrm_thesis) {
    $account = $this->currentUser();
    $langcode = $yabrm_thesis->language()->getId();
    $langname = $yabrm_thesis->language()->getName();
    $languages = $yabrm_thesis->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_thesis_storage = $this->entityTypeManager()->getStorage('yabrm_thesis');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_thesis->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_thesis->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all thesis reference revisions") || $account->hasPermission('administer thesis reference entities')));
    $delete_permission = (($account->hasPermission("delete all thesis reference revisions") || $account->hasPermission('administer thesis reference entities')));

    $rows = [];

    $vids = $yabrm_thesis_storage->revisionIds($yabrm_thesis);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\ThesisReferenceInterface $revision */
      $revision = $yabrm_thesis_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_thesis->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_thesis.revision', [
            'yabrm_thesis' => $yabrm_thesis->id(),
            'yabrm_thesis_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_thesis->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_thesis.revision_revert', [
                'yabrm_thesis' => $yabrm_thesis->id(),
                'yabrm_thesis_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_thesis.revision_delete', [
                'yabrm_thesis' => $yabrm_thesis->id(),
                'yabrm_thesis_revision' => $vid,
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

    $build['yabrm_thesis_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
