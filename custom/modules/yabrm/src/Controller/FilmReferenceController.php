<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\FilmReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FilmReferenceController.
 *
 *  Returns responses for Film reference routes.
 */
class FilmReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Film reference  revision.
   *
   * @param int $yabrm_film_revision
   *   The Film reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_film_revision) {
    $yabrm_film = $this->entityTypeManager()->getStorage('yabrm_film')->loadRevision($yabrm_film_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_film');

    return $view_builder->view($yabrm_film);
  }

  /**
   * Page title callback for a Film reference  revision.
   *
   * @param int $yabrm_film_revision
   *   The Film reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_film_revision) {
    $yabrm_film = $this->entityTypeManager()->getStorage('yabrm_film')->loadRevision($yabrm_film_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_film->label(),
      '%date' => $this->dateFormatter->format($yabrm_film->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Film reference .
   *
   * @param \Drupal\yabrm\Entity\FilmReferenceInterface $yabrm_film
   *   A Film reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(FilmReferenceInterface $yabrm_film) {
    $account = $this->currentUser();
    $langcode = $yabrm_film->language()->getId();
    $langname = $yabrm_film->language()->getName();
    $languages = $yabrm_film->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_film_storage = $this->entityTypeManager()->getStorage('yabrm_film');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_film->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_film->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all film reference revisions") || $account->hasPermission('administer film reference entities')));
    $delete_permission = (($account->hasPermission("delete all film reference revisions") || $account->hasPermission('administer film reference entities')));

    $rows = [];

    $vids = $yabrm_film_storage->revisionIds($yabrm_film);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\FilmReferenceInterface $revision */
      $revision = $yabrm_film_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_film->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_film.revision', [
            'yabrm_film' => $yabrm_film->id(),
            'yabrm_film_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_film->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_film.revision_revert', [
                'yabrm_film' => $yabrm_film->id(),
                'yabrm_film_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_film.revision_delete', [
                'yabrm_film' => $yabrm_film->id(),
                'yabrm_film_revision' => $vid,
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

    $build['yabrm_film_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
