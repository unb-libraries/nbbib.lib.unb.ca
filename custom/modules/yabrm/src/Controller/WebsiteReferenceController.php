<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Renderer;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\WebsiteReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WebsiteReferenceController.
 *
 *  Returns responses for Website reference routes.
 */
class WebsiteReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Website reference  revision.
   *
   * @param int $yabrm_website_revision
   *   The Website reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_website_revision) {
    $yabrm_website = $this->entityTypeManager()->getStorage('yabrm_website')->loadRevision($yabrm_website_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_website');

    return $view_builder->view($yabrm_website);
  }

  /**
   * Page title callback for a Website reference  revision.
   *
   * @param int $yabrm_website_revision
   *   The Website reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_website_revision) {
    $yabrm_website = $this->entityTypeManager()->getStorage('yabrm_website')->loadRevision($yabrm_website_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_website->label(),
      '%date' => $this->dateFormatter->format($yabrm_website->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Website reference .
   *
   * @param \Drupal\yabrm\Entity\WebsiteReferenceInterface $yabrm_website
   *   A Website reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(WebsiteReferenceInterface $yabrm_website) {
    $account = $this->currentUser();
    $langcode = $yabrm_website->language()->getId();
    $langname = $yabrm_website->language()->getName();
    $languages = $yabrm_website->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_website_storage = $this->entityTypeManager()->getStorage('yabrm_website');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_website->label(),
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_website->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all website reference revisions") || $account->hasPermission('administer website reference entities')));
    $delete_permission = (($account->hasPermission("delete all website reference revisions") || $account->hasPermission('administer website reference entities')));

    $rows = [];

    $vids = $yabrm_website_storage->revisionIds($yabrm_website);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\WebsiteReferenceInterface $revision */
      $revision = $yabrm_website_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_website->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_website.revision', [
            'yabrm_website' => $yabrm_website->id(),
            'yabrm_website_revision' => $vid,
          ]))->toString();
        }
        else {
          $link = $yabrm_website->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_website.revision_revert', [
                'yabrm_website' => $yabrm_website->id(),
                'yabrm_website_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_website.revision_delete', [
                'yabrm_website' => $yabrm_website->id(),
                'yabrm_website_revision' => $vid,
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

    $build['yabrm_website_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
