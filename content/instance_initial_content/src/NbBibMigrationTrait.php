<?php

namespace Drupal\instance_initial_content;

/**
 * Trait for defining global properties for NBBIB migrations.
 */
trait NbBibMigrationTrait {

  /**
   * Get the current migrations and collections.
   *
   * @return array
   *   An associative array containing applicable migration IDs / collections.
   */
  public function getMigrations() {
    return [
      'nbbib_1_religion_anglican_1_journal_articles' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_2_books' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_3_book_sections' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_4_theses' => 'Religion: Anglican',
    ];
  }

  /**
   * Get the Zotero to NBBIB type mappings.
   *
   * @return array
   *   An associative array keyed by Zotero types and valued with NBBIB types.
   */
  public function getZoteroTypeMappings() {
    return [
      'journalArticle' => 'yabrm_journal_article',
      'book' => 'yabrm_book',
      'bookSection' => 'yabrm_book_section',
      'thesis' => 'yabrm_thesis',
    ];
  }

}
