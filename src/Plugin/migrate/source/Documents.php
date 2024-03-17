<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\Core\Site\Settings;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides a 'Documents' migrate source.
 *
 * @MigrateSource(
 *  id = "wp_to_drupal_documents",
 *  source_module = "wp_to_drupal_migrate"
 * )
 */
class Documents extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('wp_posts', 'posts')
      ->fields('posts');
    $query->join('wp_postmeta', 'meta', 'meta.post_id = posts.ID');
    $query->fields('meta', ['meta_value'])
      ->condition('meta.meta_key', '_wp_attached_file')
      ->condition('posts.post_type', 'attachment')
      ->condition(
        'posts.post_mime_type',
        [
          'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          'application/msword',
          'application/pdf',
        ],
        'IN'
      );
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    $path = $row->getSourceProperty('meta_value');
    $base_source_path = Settings::get('wp_to_drupal_migrate_path_to_migrated_files', 'http://example.org/wp-content/uploads/');
    $row->setSourceProperty('url', $base_source_path . $path);
    $row->setSourceProperty('file_destination_path', 'public://migrated/' . $path);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'ID',
      'post_title',
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'ID' =>
        [
          'type' => 'integer',
          'alias' => 'posts',
        ],
    ];
  }

}
