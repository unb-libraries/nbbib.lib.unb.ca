<?php

namespace Drupal\yabrm\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

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

  const APPLIES_ENTITY_TYPE = 'yabrm_biblio_reference';

  /**
   * Only enabled for an index that indexes the yabrm_biblio_reference entity.
   *
   * {@inheritdoc}
   */
  public static function supportsIndex(IndexInterface $index) {
    $supported_entity_types = [self::APPLIES_ENTITY_TYPE];
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
        'label' => $this->t('Author(s)'),
        'description' => $this->t('All contributors identified as Author for this bibliographic reference'),
        'type' => 'search_api_html',
        'is_list' => TRUE,
        'processor_id' => $this->getPluginId(),
      ];
      $properties['bibliographic_authors'] = new ProcessorProperty($definition);
    }

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function addFieldValues(ItemInterface $item) {
    $entity = $item->getDatasource();
    if ($entity->getEntityTypeId() == self::APPLIES_ENTITY_TYPE) {
      $yabrm_entity = $item->getOriginalObject()->getValue();

      // Contributors with role 'Author'.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_authors');
      foreach ($fields as $field) {
        $authors = $yabrm_entity->getContributors('Author');
        foreach ($authors as $author) {
          $field->addValue($author->toLink()->toString());
        }
      }
    }
  }

}
