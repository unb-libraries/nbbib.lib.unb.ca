<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\ConferenceReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ConferenceReferenceController.
 *
 *  Returns responses for Conference reference routes.
 */
class ConferenceReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Conference reference  revision.
   *
   * @param int $yabrm_conference_revision
   *   The Conference reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_conference_revision) {
    $yabrm_conference = $this->entityTypeManager()->getStorage('yabrm_conference')->loadRevision($yabrm_conference_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_conference');

    return $view_builder->view($yabrm_conference);
  }

  /**
   * Page title callback for a Conference reference  revision.
   *
   * @param int $yabrm_conference_revision
   *   The Conference reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_conference_revision) {
    $yabrm_conference = $this->entityTypeManager()->getStorage('yabrm_conference')->loadRevision($yabrm_conference_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_conference->label(),
      '%date' => $this->dateFormatter->format($yabrm_conference->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Conference reference .
   *
   * @param \Drupal\yabrm\Entity\ConferenceReferenceInterface $yabrm_conference
   *   A Conference reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ConferenceReferenceInterface $yabrm_conference) {
    $account = $this->currentUser();
    $langcode = $yabrm_conference->language()->getId();
    $langname = $yabrm_conference->language()->getName();
    $languages = $yabrm_conference->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_conference_storage = $this->entityTypeManager()->getStorage('yabrm_conference');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_conference->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_conference->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all conference reference revisions") || $account->hasPermission('administer conference reference entities')));
    $delete_permission = (($account->hasPermission("delete all conference reference revisions") || $account->hasPermission('administer conference reference entities')));

    $rows = [];

    $vids = $yabrm_conference_storage->revisionIds($yabrm_conference);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\ConferenceReferenceInterface $revision */
      $revision = $yabrm_conference_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_conference->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_conference.revision', [
            'yabrm_conference' => $yabrm_conference->id(),
            'yabrm_conference_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_conference->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_conference.revision_revert', [
                'yabrm_conference' => $yabrm_conference->id(),
                'yabrm_conference_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_conference.revision_delete', [
                'yabrm_conference' => $yabrm_conference->id(),
                'yabrm_conference_revision' => $vid,
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

    $build['yabrm_conference_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
