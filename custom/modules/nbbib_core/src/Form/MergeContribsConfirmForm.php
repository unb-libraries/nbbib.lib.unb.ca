<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\yabrm\Entity\BibliographicContributor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MergeContribsConfirmForm class. Confirms the merging of contributors.
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
   *   The messenger service.
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

    $dupe_names = [];

    // Extract current contributor id.
    if ($yabrm_contributor) {
      $this->cid = $yabrm_contributor;
      // Load contributor.
      $contrib = BibliographicContributor::load($yabrm_contributor);
      // Get name.
      $contrib_inst = $contrib->getInstitutionName();

      if ($contrib_inst) {
        $contrib_name = $contrib_inst;
      }
      else {
        $contrib_name = trim(
          $contrib->getFirstName() . ' ' . $contrib->getLastName()
        );
      }
    }

    // Build target contrib name markup.
    $contrib_name_markup = "
      <a href='/yabrm/yabrm_contributor/$yabrm_contributor' rel='noopener noreferrer' target='_blank'>
        $contrib_name ($yabrm_contributor)
      </a>
    ";

    if ($duplicates) {
      // Extract individual duplicate contributor ids.
      $this->duplicates = explode('-', $duplicates);

      // For each duplicate id...
      foreach ($this->duplicates as $did) {

        if ($did) {
          // Load contributor.
          $dupe = BibliographicContributor::load($did);
          // Get name and add to list.
          $dupe_inst = $dupe->getInstitutionName();

          if ($dupe_inst) {
            $dupe_names[$did] = $contrib_inst;
          }
          else {
            $dupe_names[$did] = trim(
              $dupe->getFirstName() . ' ' . $dupe->getLastName()
            );
          }
        }
      }
    }

    // Build duplicate names markup.
    $dupe_names_markup = "<ul class='dupe-contribs'>";

    foreach ($dupe_names as $did => $dupe_name) {
      $li = "
        <li class='dupe-contrib'>
          <a href='/yabrm/yabrm_contributor/$did' rel='noopener noreferrer' target='_blank'>
            $dupe_name ($did)
          </a>
        </li>
      ";

      $dupe_names_markup .= $li;
    }

    $dupe_names_markup .= "</ul>";

    // Add duplicate details to confirmation form.
    $form['duplicates'] = [
      '#type' => 'item',
      '#title' => $this->t('<b>The following contributors:</b>'),
      '#markup' => $this->t($dupe_names_markup),
    ];

    // Add merge target details to confirmation form.
    $form['target'] = [
      '#type' => 'item',
      '#title' => $this->t('<b>Will be merged into:</b>'),
      '#markup' => $this->t($contrib_name_markup),
    ];

    // Add warning.
    $warning = "
      Merging will delete the selected contributor(s) and reassign all
      bibliographic references to the target contributor.
    ";

    $form['warning'] = [
      '#type' => 'markup',
      '#markup' => $this->t("<b>WARNING:</b> $warning"),
    ];

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
    // Get target contributor name.
    $contributor = BibliographicContributor::load($this->cid);
    $name = $contributor->getName();

    // If duplicates have been selected...
    if ($this->duplicates) {
      // Retrieve individual duplicate ids.
      $dids = $this->duplicates;

      // Set up batch process.
      $batch = [
        'title' => $this->t('Merging contributors'),
        'operations' => [
          [
            '\Drupal\nbbib_core\Form\MergeContribsConfirmForm::queryReferences',
            [$this->entityTypeManager, $dids],
          ],
        ],
        'init_message'     => $this->t('Initializing'),
        'progress_message' => $this->t('Completed @current out of @total'),
        'error_message'    => $this->t('An error occurred during processing'),
        'finished' => '\Drupal\nbbib_core\Form\MergeContribsConfirmForm::mergeContribsDone',
      ];

      // Run batch.
      batch_set($batch);

      // Prepare confirmation message.
      $msg = "Merged into the $name Bibliographic Contributor.";
      $this->messenger->addMessage($msg);

      // Flush all caches and wait.
      drupal_flush_all_caches();
      sleep(count($dids) * 3);

      // Redirect to contributor main display.
      $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
    }
    else {
      // Display confirmation message.
      $msg = "No items selected for merging.";
      $this->messenger->addError($msg);
      // Redirect to contributor merge display.
      $form_state->setRedirect('nbbib_core.merge_contribs', ['yabrm_contributor' => $this->cid]);
    }

  }

  /**
   * Query references - batch operation callback.
   */
  public static function queryReferences(EntityTypeManagerInterface $entityTypeManager, $dids, &$context) {

    // Initialize batch sandbox.
    if (!isset($context['sandbox']['progress'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['paragraphs'] = [];
      $context['sandbox']['reindex'] = [];
    }

    if ($dids[$context['sandbox']['progress']]) {
      // Query for reference relationships (paragraphs) that reference id.
      $query = $entityTypeManager->getStorage('paragraph');

      $context['sandbox']['paragraphs'] = $query->getQuery()
        ->condition('field_yabrm_contributor_person', $did)
        ->execute();
    }
  }

  /**
   * Merge contributors batch callback.
   */
  public static function mergeContribs(MergeContribsConfirmForm $form, $dids, &$context) {

    // For each duplicate id...
    foreach ($dids as $did) {

      if ($did) {
        // Query for reference relationships (paragraphs) that reference id.
        $query = $form->entityTypeManager->getStorage('paragraph');

        $paragraphs = $query->getQuery()
          ->condition('field_yabrm_contributor_person', $did)
          ->execute();

        // For each paragraph...
        foreach ($paragraphs as $pid) {
          // Load paragraph.
          $paragraph = Paragraph::load($pid);
          // Replace dupe contributor id for target contributor id ($this->cid).
          $paragraph->set('field_yabrm_contributor_person', $form->cid);
          // Save paragraph.
          $paragraph->save();

          // Reindex references affected by paragraph change.
          $items = [];
          // Get current language.
          $language = $form->languageManager->getCurrentLanguage()->getId();
          // Load Search API index.
          $index_storage = $form->entityTypeManager
            ->getStorage('search_api_index');
          $index = $index_storage->load('references_nbbib_lib_unb_ca');

          // Query for books that contain the paragraphs.
          $query = $form->entityTypeManager->getStorage('yabrm_book');

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
          $query = $form->entityTypeManager->getStorage('yabrm_book_section');

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
          $query = $form->entityTypeManager->getStorage('yabrm_journal_article');

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
          $query = $form->entityTypeManager->getStorage('yabrm_thesis');

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
        }

        // Delete duplicate contributor.
        $duplicate = BibliographicContributor::load($did);
        $duplicate->delete();
        // Update batch message.
        $context['message'] = "Reindexing...";
      }
    }
  }

}
