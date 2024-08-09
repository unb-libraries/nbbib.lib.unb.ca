<?php

namespace Drupal\yabrm\Plugin\search_api\processor;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Render\Renderer;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Adds additional citation information to a search api reference.
 *
 * @SearchApiProcessor(
 *   id = "index_bibliographic_info",
 *   label = @Translation("Additional YABRM Fields"),
 *   description = @Translation("Add contributor information, etc."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class IndexReferenceInformation extends ProcessorPluginBase {

  /**
   * For service dependency injection.
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * For service dependency injection.
   *
   * @var Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * Class constructor.
   *
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin identifier.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   Path matcher service dependency injection.
   * @param Drupal\Core\Render\Renderer $renderer
   *   Config factory service dependency injection.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManager $entity_type_manager,
    Renderer $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->renderer = $renderer;
  }

  /**
   * Object create function.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interface.
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The plugin identifier.
   * @param mixed $plugin_definition
   *   The plugin definition.
   *
   * @return static
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('renderer')
    );
  }

  const APPLIES_ENTITY_TYPES = [
    'yabrm_biblio_reference',
    'yabrm_journal_article',
    'yabrm_book',
    'yabrm_book_section',
    'yabrm_thesis',
  ];

  const ENTITY_TYPE_LABELS = [
    'Drupal\yabrm\Entity\BibliographicReference' => 'Reference',
    'Drupal\yabrm\Entity\JournalArticleReference' => 'Journal Article',
    'Drupal\yabrm\Entity\BookReference' => 'Book',
    'Drupal\yabrm\Entity\BookSectionReference' => 'Book Section',
    'Drupal\yabrm\Entity\ThesisReference' => 'Thesis',
  ];

  /**
   * Only enabled for an index that indexes the yabrm_biblio_reference entity.
   *
   * {@inheritdoc}
   */
  public static function supportsIndex(IndexInterface $index) {
    $supported_entity_types = self::APPLIES_ENTITY_TYPES;
    foreach ($index->getDatasources() as $datasource) {
      if (in_array($datasource->getEntityTypeId(), $supported_entity_types)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions(DatasourceInterface $datasource = NULL) {
    $properties = [];

    if (!$datasource) {
      $definition = [
        'label' => $this->t('ID'),
        'description' => $this->t('The entity UUID of the bibliographic reference.'),
        'type' => 'integer',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['id'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Reference Type'),
        'description' => $this->t('The type of the bibliographic reference.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_type'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Reference Title'),
        'description' => $this->t('The title of the bibliographic reference, regardless of type.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_title'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Publisher'),
        'description' => $this->t('The publisher of the referenced item.'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['publisher'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Publication Place'),
        'description' => $this->t('The place of publication of the referenced item.'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['place'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Publication Year'),
        'description' => $this->t('The publication year.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['publication_year'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Publication Year Slider'),
        'description' => $this->t('Publication year for facet range slider.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['publication_year_slider'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Author(s)'),
        'description' => $this->t('All contributors identified as Author for this bibliographic reference'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_authors'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Contributor(s)'),
        'description' => $this->t('All contributors for this bibliographic reference'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_contribs'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Collection(s)'),
        'description' => $this->t('All associated with this bibliographic reference'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['collections'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Collection(s) - String'),
        'description' => $this->t('All associated with this bibliographic reference'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['collections_string'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Sortable Date'),
        'description' => $this->t('A sortable date field based on incomplete dates'),
        'type' => 'integer',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['date_sort'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Reference Date'),
        'description' => $this->t('A formatted date field used for display'),
        'type' => 'string',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['date_display'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Published'),
        'description' => $this->t('A flag indicating if the reference record is published.'),
        'type' => 'boolean',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['published'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Last Updated'),
        'description' => $this->t('A timestamp for the last time the entity was updated.'),
        'type' => 'timestamp',
        'processor_id' => $this->getPluginId(),
      ];
      $properties['changed'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Reference Citation'),
        'description' => $this->t('The citation of the reference.'),
        'type' => 'search_api_html',
        'is_list' => FALSE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_citation'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Languages'),
        'description' => $this->t('Languages associated with the reference.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['languages'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Topic Names'),
        'description' => $this->t('Names of topics associated with the reference.'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['topic_names'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Topic Names - String'),
        'description' => $this->t('Names of topics associated with the reference.'),
        'type' => 'string',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['topic_names_string'] = new ProcessorProperty($definition);

      $definition = [
        'label' => $this->t('Topics'),
        'description' => $this->t('Topics associated with the reference.'),
        'type' => 'integer',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['topics'] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getDatasource();
    if (in_array($entity->getEntityTypeId(), self::APPLIES_ENTITY_TYPES)) {
      $yabrm_entity = $item->getOriginalObject()->getValue();

      // Entity ID.
      if (method_exists($yabrm_entity, 'uuid')) {
        $id = $yabrm_entity->uuid();
      }
      else {
        $id = NULL;
      }

      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'id');
      foreach ($fields as $field) {
        $field->addValue($id);
      }
      
      // The type of reference.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_type');
      foreach ($fields as $field) {
        $class_name = get_class($yabrm_entity);
        if (!empty(self::ENTITY_TYPE_LABELS[$class_name])) {
          $field->addValue(
            self::ENTITY_TYPE_LABELS[$class_name]
          );
        }
      }

      // The common title field.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_title');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->getName());
      }

      // Publisher field.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'publisher');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->getPublisher());
      }

      // Publication place field.
      if (method_exists($yabrm_entity, 'getPlace')) {
        $place = $yabrm_entity->getPlace();
      }
      else {
        $place = NULL;
      }

      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'place');
      foreach ($fields as $field) {
        $field->addValue($place);
      }

      // Publication year field.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'publication_year');
      foreach ($fields as $field) {
        $value = intval($yabrm_entity->getPublicationYear());
        $field->addValue($value);
      }

      // Publication year slider field.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'publication_year_slider');
      foreach ($fields as $field) {
        $value = intval($yabrm_entity->getPublicationYear());
        if ($value != 0) {
          $field->addValue($value);
        }
      }

      // Contributors with role 'Author'.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_authors');
      foreach ($fields as $field) {
        $authors = $yabrm_entity->getContributors('Author');
        foreach ($authors as $author) {
          if (!empty($author)) {
            $field->addValue($author->toLink()->toString());
          }
        }
      }

      // Contributors.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_contribs');
      foreach ($fields as $field) {
        $authors = $yabrm_entity->getContributors();
        foreach ($authors as $author) {
          if (!empty($author)) {
            $field->addValue($author->getName());
          }
        }
      }

      // Collections.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'collections');
      foreach ($fields as $field) {
        $collections = $yabrm_entity->getCollections();
        foreach ($collections as $collection) {
          if (!empty($collection)) {
            $field->addValue($collection->toLink()->toString());
          }
        }
      }

      // Collections - String.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'collections_string');
      foreach ($fields as $field) {
        $collections = $yabrm_entity->getCollections();
        foreach ($collections as $collection) {
          if (!empty($collection)) {
            $field->addValue($collection->getName());
          }
        }
      }

      // Languages.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'languages');
      foreach ($fields as $field) {
        $languages = $yabrm_entity->language->getValue();
        foreach ($languages as $language) {
          $field->addValue($language['value']);
        }
      }

      // Topics (by name).
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'topic_names');
      foreach ($fields as $field) {
        $topics = $yabrm_entity->getTopics();
        foreach ($topics as $topic) {
          if (!empty($topic)) {
            $field->addValue($topic->toLink()->toString());
          }
        }
      }

      // Topics (by name) - String.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'topic_names_string');
      foreach ($fields as $field) {
        $topics = $yabrm_entity->getTopics();
        foreach ($topics as $topic) {
          if (!empty($topic)) {
            $field->addValue($topic->getName());
          }
        }
      }

      // Topics (by ID).
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'topics');
      foreach ($fields as $field) {
        $topics = $yabrm_entity->getTopics();
        foreach ($topics as $topic) {
          if (!empty($topic)) {
            $field->addValue($topic->id());
          }
        }
      }

      // Sortable date.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'date_sort');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->getSortTimestamp());
      }

      // Display date.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'date_display');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->getDisplayDate());
      }

      // Publishing status.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'published');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->isPublished());
      }

      // Last updated.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'changed');
      foreach ($fields as $field) {
        $field->addValue($yabrm_entity->getChangedTime());
      }

      // Citation view mode.
      $view_builder = $this->entityTypeManager->getViewBuilder($yabrm_entity->getEntityTypeId());
      $storage = $this->entityTypeManager->getStorage($yabrm_entity->getEntityTypeId());
      $build = $view_builder->view($yabrm_entity, 'citation');

      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_citation');

      foreach ($fields as $field) {
        $field->addValue($this->renderer->renderRoot($build));
      }
    }
  }

}
