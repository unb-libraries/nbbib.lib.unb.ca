<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\views\Views;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * EditSubjectsForm class.
 */
class MergeContribsConfirmForm extends ConfirmFormBase {
  /**
   * ID of the contributor being merged into.
   *
   * @var int
   */
  protected $cid;

  /**
   * IDs of the duplicate contributors selected for merging.
   *
   * @var string
   */
  protected $duplicates;

  /**
   * The Entity Type Manager service.
   *
   * @var Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Language Manager service.
   *
   * @var Drupal\Core\Language\LanguageManager
   */
  protected $languageManager;

  /**
   * The Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Class constructor.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   * @param Drupal\Core\Language\LanguageManager $language_manager
   *   The language manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The entity type manager service.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    LanguageManager $language_manager,
    MessengerInterface $messenger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->languageManager = $language_manager;
    $this->messenger = $messenger;
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
      $container->get('entity_type.manager'),
      $container->get('language_manager'),
      $container->get('messenger'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nbbib_core_merge_contribs_confirm_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want want to merge contributors?');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(
    array $form,
    FormStateInterface $form_state,
    $yabrm_contributor = NULL,
    $duplicates = NULL) {

    $this->cid = $yabrm_contributor;
    $this->duplicates = $duplicates;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Extract individual duplicate ids.
    $dids = explode('-', $this->duplicates);

    // For each duplicate id...
    foreach ($dids as $did) {

      if ($did) {
        // Query for reference relationships (paragraphs) that reference id.
        $query = $this->entityTypeManager->getStorage('paragraph');

        $paragraphs = $query->getQuery()
          ->condition('field_yabrm_contributor_person', $did)
          ->execute();

        // For each paragraph...
        foreach ($paragraphs as $pid) {
          // Load paragraph.
          $paragraph = Paragraph::load($pid);
          // Replace dupe contributor id for target contributor id ($this->cid).
          $paragraph->set('field_yabrm_contributor_person', $this->cid);
          // Save paragraph.
          $paragraph->save();

          // Reindex references affected by paragraph change.
          $items = [];
          // Get current language.
          $language = $this->languageManager->getCurrentLanguage()->getId();
          // Load Search API index.
          $index_storage = $this->entityTypeManager
            ->getStorage('search_api_index');
          $index = $index_storage->load('references_nbbib_lib_unb_ca');

          // Query for books that contain the paragraphs.
          $query = $this->entityTypeManager->getStorage('yabrm_book');

          $books = $query->getQuery()
            ->condition('contributors', $pid, 'IN')
            ->execute();

          // For each book...
          foreach ($books as $bid) {
            // Add book to index tracking.
            // Prepare and add specific item to list for reindex.
            $item_id = 'entity:yabrm_book/' . $bid . ':' . $language;
            $items[$item_id] = $index->loadItem($item_id);
          }

          // Query for book sections that contain the paragraphs.
          $query = $this->entityTypeManager->getStorage('yabrm_book_section');

          $sections = $query->getQuery()
            ->condition('contributors', $pid, 'IN')
            ->execute();

          foreach ($sections as $sid) {
            // Add book section to index tracking.
            // Prepare and add item.
            $item_id = 'entity:yabrm_book_section/' . $sid . ':' . $language;
            $items[$item_id] = $index->loadItem($item_id);
          }

          // Query for journal articles that contain the paragraphs.
          $query = $this->entityTypeManager->getStorage('yabrm_journal_article');

          $articles = $query->getQuery()
            ->condition('contributors', $pid, 'IN')
            ->execute();

          foreach ($articles as $aid) {
            // Add journal article to index tracking.
            // Prepare and add item.
            $item_id = 'entity:yabrm_journal_article/' . $aid . ':' . $language;
            $items[$item_id] = $index->loadItem($item_id);
          }

          // Query for theses that contain the paragraphs.
          $query = $this->entityTypeManager->getStorage('yabrm_thesis');

          $theses = $query->getQuery()
            ->condition('contributors', $pid, 'IN')
            ->execute();

          foreach ($theses as $tid) {
            // Add thesis to index tracking.
            // Prepare and add item.
            $item_id = 'entity:yabrm_thesis/' . $tid . ':' . $language;
            $items[$item_id] = $index->loadItem($item_id);
          }

          // Index items.
          $index->indexSpecificItems($items);

          // Clear citations view's cache.
          $view = Views::getView('nb_bibliography_citations');
          $view->storage->invalidateCaches();
        }

        // Delete duplicate contributor.
        $duplicate = BibliographicContributor::load($did);
        $duplicate->delete();
      }
    }

    // Get target contributor name.
    $contributor = BibliographicContributor::load($this->cid);
    $name = $contributor->getName();

    // Display confirmation message.
    $msg = "
      Merged into the $name Bibliographic Contributor. Please wait a moment for
      the list of bibliography items to update.
    ";

    $this->messenger->addMessage($msg);
    $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
  }

}
