<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\Core\Site\Settings;
use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides a 'Videos' migrate source.
 *
 * @MigrateSource(
 *  id = "videos",
 *  source_module = "wp_to_drupal_migrate"
 * )
 */
class Videos extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('wp_posts', 'posts')
      ->fields('posts')
      ->condition('posts.post_type', 'attachment')
      ->condition('posts.post_mime_type', 'video%', 'LIKE');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    // Code deleted.
    $base_source_path = Settings::get('wp_to_drupal_migrate_path_to_migrated_files', 'http://example.com/wp-content/uploads/');
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
