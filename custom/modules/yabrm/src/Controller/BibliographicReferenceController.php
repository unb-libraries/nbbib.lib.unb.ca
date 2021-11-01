<?php

namespace Drupal\yabrm\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\yabrm\Entity\BibliographicReferenceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BibliographicReferenceController.
 *
 *  Returns responses for Bibliographic Reference routes.
 */
class BibliographicReferenceController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Bibliographic Reference  revision.
   *
   * @param int $yabrm_biblio_reference_revision
   *   The Bibliographic Reference  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($yabrm_biblio_reference_revision) {
    $yabrm_biblio_reference = $this->entityTypeManager()->getStorage('yabrm_biblio_reference')->loadRevision($yabrm_biblio_reference_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('yabrm_biblio_reference');

    return $view_builder->view($yabrm_biblio_reference);
  }

  /**
   * Page title callback for a Bibliographic Reference  revision.
   *
   * @param int $yabrm_biblio_reference_revision
   *   The Bibliographic Reference  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($yabrm_biblio_reference_revision) {
    $yabrm_biblio_reference = $this->entityTypeManager()->getStorage('yabrm_biblio_reference')->loadRevision($yabrm_biblio_reference_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $yabrm_biblio_reference->label(),
      '%date' => $this->service->get('date.formatter')->format($yabrm_biblio_reference->getRevisionCreationTime())
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Bibliographic Reference .
   *
   * @param \Drupal\yabrm\Entity\BibliographicReferenceInterface $yabrm_biblio_reference
   *   A Bibliographic Reference  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(BibliographicReferenceInterface $yabrm_biblio_reference) {
    $account = $this->currentUser();
    $langcode = $yabrm_biblio_reference->language()->getId();
    $langname = $yabrm_biblio_reference->language()->getName();
    $languages = $yabrm_biblio_reference->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $yabrm_biblio_reference_storage = $this->entityTypeManager()->getStorage('yabrm_biblio_reference');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', [
      '@langname' => $langname,
      '%title' => $yabrm_biblio_reference->label()
    ]) : $this->t('Revisions for %title', [
      '%title' => $yabrm_biblio_reference->label()
    ]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all bibliographic reference revisions") || $account->hasPermission('administer bibliographic reference entities')));
    $delete_permission = (($account->hasPermission("delete all bibliographic reference revisions") || $account->hasPermission('administer bibliographic reference entities')));

    $rows = [];

    $vids = $yabrm_biblio_reference_storage->revisionIds($yabrm_biblio_reference);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\yabrm\BibliographicReferenceInterface $revision */
      $revision = $yabrm_biblio_reference_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->service->get('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $yabrm_biblio_reference->getRevisionId()) {
          $link = Link::fromTextAndUrl($date, new Url('entity.yabrm_biblio_reference.revision', [
            'yabrm_biblio_reference' => $yabrm_biblio_reference->id(),
            'yabrm_biblio_reference_revision' => $vid
          ]))->toString();
        }
        else {
          $link = $yabrm_biblio_reference->toLink($date)->toString();
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
              'url' => Url::fromRoute('entity.yabrm_biblio_reference.revision_revert', [
                'yabrm_biblio_reference' => $yabrm_biblio_reference->id(),
                'yabrm_biblio_reference_revision' => $vid
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.yabrm_biblio_reference.revision_delete', [
                'yabrm_biblio_reference' => $yabrm_biblio_reference->id(),
                'yabrm_biblio_reference_revision' => $vid
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

    $build['yabrm_biblio_reference_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
