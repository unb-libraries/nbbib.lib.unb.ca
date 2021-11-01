<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BibliographicContributorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BibliographicContributorController.
 *
 *  Returns responses for Bibliographic Contributor routes.
 */
class BibliographicContributorController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * For services dependency injection.
   *
   * @var Symfony\Component\DependencyInjection\ContainerInterface
   */
  protected $service;

  /**
   * Class constructor.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $service_container
   *   The container interface for using services via dependency injection.
   */
  public function __construct(ContainerInterface $service_container) {
    $this->service = $service_container;
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
      $container->get('service_container')
    );
  }

  /**
   * Displays a Bibliographic Contributor  revision.
   *
   * @param int $yabrm_contributor_revision
   *   The Bibliographic Contributor  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_contributor_revision) {
    $yabrm_contributor = $this->entityTypeManager()->getStorage('yabrm_contributor')->loadRevision($yabrm_contributor_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_contributor');

    return $view_builder->view($yabrm_contributor);
  }

  /**
   * Page title callback for a Bibliographic Contributor  revision.
   *
   * @param int $yabrm_contributor_revision
   *   The Bibliographic Contributor  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_contributor_revision) {
    $yabrm_contributor = $this->entityTypeManager()->getStorage('yabrm_contributor')->loadRevision($yabrm_contributor_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_contributor->label(),
      '%date' => $this->service->get('date.formatter')->format($yabrm_contributor->getRevisionCreationTime())
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Bibliographic Contributor .
   *
   * @param \Drupal\yabrm\Entity\BibliographicContributorInterface $yabrm_contributor
   *   A Bibliographic Contributor  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BibliographicContributorInterface $yabrm_contributor) {
    $account = $this->currentUser();
    $langcode = $yabrm_contributor->language()->getId();
    $langname = $yabrm_contributor->language()->getName();
    $languages = $yabrm_contributor->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_contributor_storage = $this->entityTypeManager()->getStorage('yabrm_contributor');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_contributor->label()
    ]) : $this->t('Revisions for %title', ['%title' => $yabrm_contributor->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all bibliographic contributor revisions") || $account->hasPermission('administer bibliographic contributor entities')));
    $delete_permission = (($account->hasPermission("delete all bibliographic contributor revisions") || $account->hasPermission('administer bibliographic contributor entities')));

    $rows = [];

    $vids = $yabrm_contributor_storage->revisionIds($yabrm_contributor);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BibliographicContributorInterface $revision */
      $revision = $yabrm_contributor_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->service->get('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_contributor->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_contributor.revision', [
            'yabrm_contributor' => $yabrm_contributor->id(),
            'yabrm_contributor_revision' => $vid
          ]))->toString();
        }
        else {
          $link = $yabrm_contributor->toLink($date)->toString();
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->service->get('renderer')->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList()
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
              'url' => Url::fromRoute('entity.yabrm_contributor.revision_revert', [
                'yabrm_contributor' => $yabrm_contributor->id(),
                'yabrm_contributor_revision' => $vid
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_contributor.revision_delete', [
                'yabrm_contributor' => $yabrm_contributor->id(),
                'yabrm_contributor_revision' => $vid
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

    $build['yabrm_contributor_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
