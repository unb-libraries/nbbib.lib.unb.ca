<?php

namespace Drupal\nbbib\Plugin\search_api\processor;

use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\IndexInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the parent issue and title info to indexed pages.
 *
 * @SearchApiProcessor(
 *   id = "index_bibliographic_info",
 *   label = @Translation("Index Parent Information for Page"),
 *   description = @Translation("Add a page's parent issue and title information to index."),
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class IndexPageParentPageInfo extends ProcessorPluginBase {

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
        'description' => $this->t('The contributors labelled as Author for this bibliographic reference'),
        'type' => 'string',
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

      // Digital Issue ID.
      $fields = $this->getFieldsHelper()
        ->filterForPropertyPath($item->getFields(), NULL, 'bibliographic_authors');
      foreach ($fields as $field) {
        $
        $field->addValue($yabrm_entity->id());
      }

    }
  }

}
