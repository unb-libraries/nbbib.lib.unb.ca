<?php

namespace Drupal\nbbib_core\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
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
    return new Url('nbbib_core.merge_contribs', ['yabrm_contributor' => $this->cid]);
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
        $first = $contrib->getFirstName();
        $last = $contrib->getLastName();
        $pre = $contrib->getPrefix();
        $suf = $contrib->getSuffix();

        $contrib_name = trim("$pre $first $last $suf");
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
            $dupe_names[$did] = $dupe_inst;
          }
          else {
            $dupe_names[$did] = trim(
              $dupe->getPrefix() . ' ' . $dupe->getFirstName() . ' ' .
              $dupe->getLastName() . ' ' . $dupe->getSuffix()
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
      bibliographic references to the target contributor. This may take a few
      seconds.
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
      $dids = array_filter($this->duplicates);

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
            // Replace dupe contrib id for target contributor id ($this->cid).
            $paragraph->set('field_yabrm_contributor_person', $this->cid);
            // Save paragraph.
            $paragraph->save();
          }

          // Delete duplicate contributor.
          $duplicate = BibliographicContributor::load($did);
          $duplicate->delete();
        }
      }

      $count = count($dids);

      // Prepare confirmation message.
      $msg = "$count record(s) merged into the $name Bibliographic Contributor.
        <br><b>Please wait a few seconds and refresh this page to see the
        updated list of citations.</b>";
      $this->messenger->addMessage($this->t($msg));

      // Redirect to contributor main display.
      $form_state->setRedirect('entity.yabrm_contributor.canonical', ['yabrm_contributor' => $this->cid]);
    }
    else {
      // Display error message.
      $msg = "No items selected for merging.";
      $this->messenger->addError($msg);
      // Redirect to contributor merge display.
      $form_state->setRedirect('nbbib_core.merge_contribs', ['yabrm_contributor' => $this->cid]);
    }
  }

}
