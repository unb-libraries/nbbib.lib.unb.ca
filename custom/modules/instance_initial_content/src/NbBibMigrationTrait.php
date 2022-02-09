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
      'nbbib_0_new_brunswickana_1_journal_articles' => 'New Brunswickana',
      'nbbib_0_new_brunswickana_2_books' => 'New Brunswickana',
      'nbbib_0_new_brunswickana_3_book_sections' => 'New Brunswickana',
      'nbbib_0_new_brunswickana_4_theses' => 'New Brunswickana',
      'nbbib_1_religion_anglican_1_journal_articles' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_2_books' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_3_book_sections' => 'Religion: Anglican',
      'nbbib_1_religion_anglican_4_theses' => 'Religion: Anglican',
      'nbbib_2_religion_baptist_1_journal_articles' => 'Religion: Baptist',
      'nbbib_2_religion_baptist_2_books' => 'Religion: Baptist',
      'nbbib_2_religion_baptist_3_book_sections' => 'Religion: Baptist',
      'nbbib_2_religion_baptist_4_theses' => 'Religion: Baptist',
      'nbbib_3_religion_buddhist_1_journal_articles' => 'Religion: Buddhist',
      'nbbib_3_religion_buddhist_2_books' => 'Religion: Buddhist',
      'nbbib_3_religion_buddhist_3_book_sections' => 'Religion: Buddhist',
      'nbbib_3_religion_buddhist_4_theses' => 'Religion: Buddhist',
      'nbbib_10_religion_mormon_1_journal_articles' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_2_books' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_3_book_sections' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_4_theses' => 'Religion: Mormon',
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
