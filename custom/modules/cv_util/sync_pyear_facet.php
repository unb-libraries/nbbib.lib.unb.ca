<?php

/**
 * @file
 * Contains sync_pyear_facet.php.
 */

// Publication year config sync.
\Drupal::logger('nbbib_core')->notice("Starting publication year config sync...");
$query = \Drupal::database()->query(
    'SELECT MIN(publication_year) AS db_min
    FROM (
    SELECT publication_year FROM yabrm_book t1
    UNION
    SELECT publication_year FROM yabrm_journal_article t2
    UNION
    SELECT publication_year FROM yabrm_book_section t3
    UNION
    SELECT publication_year FROM yabrm_thesis t4
    ) AS t5'
);
$db_min = $query->fetchAll()[0]->db_min;
// Check/configure new edge values for facet.
$config = \Drupal::service('config.factory')->getEditable('facets.facet.publication_year');
$widget = $config->get('widget');
$old_min = $widget['config']['min_value']; 
if ($db_min > 0) {
    $widget['config']['min_value'] = $db_min;
}
$widget['config']['max_value'] = date('Y');
$config->set('widget', $widget);
$config->save();
\Drupal::logger('nbbib_core')->notice(dump($widget));
