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
      'nbbib_0_new_brunswickana_1_journal_articles' => 'New Brunswick Imprints',
      'nbbib_0_new_brunswickana_2_books' => 'New Brunswick Imprints',
      'nbbib_0_new_brunswickana_3_book_sections' => 'New Brunswick Imprints',
      'nbbib_0_new_brunswickana_4_theses' => 'New Brunswick Imprints',
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
      'nbbib_4_religion_catholic_1_journal_articles' => 'Religion: Catholic',
      'nbbib_4_religion_catholic_2_books' => 'Religion: Catholic',
      'nbbib_4_religion_catholic_3_book_sections' => 'Religion: Catholic',
      'nbbib_4_religion_catholic_4_theses' => 'Religion: Catholic',
      'nbbib_5_religion_general_1_journal_articles' => 'Religion: General',
      'nbbib_5_religion_general_2_books' => 'Religion: General',
      'nbbib_5_religion_general_3_book_sections' => 'Religion: General',
      'nbbib_5_religion_general_4_theses' => 'Religion: General',
      'nbbib_6_religion_indigenous_1_journal_articles' => 'Religion: Indigenous',
      'nbbib_6_religion_indigenous_2_books' => 'Religion: Indigenous',
      'nbbib_6_religion_indigenous_3_book_sections' => 'Religion: Indigenous',
      'nbbib_6_religion_indigenous_4_theses' => 'Religion: Indigenous',
      'nbbib_7_religion_judaism_1_journal_articles' => 'Religion: Judaism',
      'nbbib_7_religion_judaism_2_books' => 'Religion: Judaism',
      'nbbib_7_religion_judaism_3_book_sections' => 'Religion: Judaism',
      'nbbib_7_religion_judaism_4_theses' => 'Religion: Judaism',
      'nbbib_8_religion_lutheran_1_journal_articles' => 'Religion: Lutheran',
      'nbbib_8_religion_lutheran_2_books' => 'Religion: Lutheran',
      'nbbib_8_religion_lutheran_3_book_sections' => 'Religion: Lutheran',
      'nbbib_8_religion_lutheran_4_theses' => 'Religion: Lutheran',
      'nbbib_9_religion_methodist_1_journal_articles' => 'Religion: Methodist',
      'nbbib_9_religion_methodist_2_books' => 'Religion: Methodist',
      'nbbib_9_religion_methodist_3_book_sections' => 'Religion: Methodist',
      'nbbib_9_religion_methodist_4_theses' => 'Religion: Methodist',
      'nbbib_10_religion_mormon_1_journal_articles' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_2_books' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_3_book_sections' => 'Religion: Mormon',
      'nbbib_10_religion_mormon_4_theses' => 'Religion: Mormon',
      'nbbib_11_religion_pentecostal_1_journal_articles' => 'Religion: Pentecostal',
      'nbbib_11_religion_pentecostal_2_books' => 'Religion: Pentecostal',
      'nbbib_11_religion_pentecostal_3_book_sections' => 'Religion: Pentecostal',
      'nbbib_11_religion_pentecostal_4_theses' => 'Religion: Pentecostal',
      'nbbib_12_religion_presbyterian_1_journal_articles' => 'Religion: Presbyterian',
      'nbbib_12_religion_presbyterian_2_books' => 'Religion: Presbyterian',
      'nbbib_12_religion_presbyterian_3_book_sections' => 'Religion: Presbyterian',
      'nbbib_12_religion_presbyterian_4_theses' => 'Religion: Presbyterian',
      'nbbib_13_religion_protestant_1_journal_articles' => 'Religion: Protestant',
      'nbbib_13_religion_protestant_2_books' => 'Religion: Protestant',
      'nbbib_13_religion_protestant_3_book_sections' => 'Religion: Protestant',
      'nbbib_13_religion_protestant_4_theses' => 'Religion: Protestant',
      'nbbib_14_religion_quaker_1_journal_articles' => 'Religion: Quaker',
      'nbbib_14_religion_quaker_2_books' => 'Religion: Quaker',
      'nbbib_14_religion_quaker_3_book_sections' => 'Religion: Quaker',
      'nbbib_14_religion_quaker_4_theses' => 'Religion: Quaker',
      'nbbib_15_religion_united_1_journal_articles' => 'Religion: United',
      'nbbib_15_religion_united_2_books' => 'Religion: United',
      'nbbib_15_religion_united_3_book_sections' => 'Religion: United',
      'nbbib_15_religion_united_4_theses' => 'Religion: United',
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
