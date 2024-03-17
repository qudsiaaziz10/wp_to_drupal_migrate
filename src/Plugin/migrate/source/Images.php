<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\Core\Site\Settings;
use Drupal\migrate\Row;

/**
 * Provides a 'Images' migrate source.
 *
 * @MigrateSource(
 *  id = "wp_to_drupal_images",
 *  source_module = "wp_to_drupal_migrate"
 * )
 */
class Images extends WpToDrupalMigrateSourceBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Will import all of the images for all of the posts since a certain date.
    $post_date = parent::getEarliestPostDate()''
    $query = $this->select('wp_posts', 'images');
    $query->join('wp_posts', 'posts', 'posts.post_date >= \'{$post_date}\'');
    $query->join('wp_postmeta', 'meta', 'meta.post_id = posts.ID AND meta.meta_key = \'_thumbnail_id\' AND meta.meta_value = images.ID');
    $query->fields('images')
      ->distinct()
      ->condition('images.post_type', 'attachment')
      ->condition('images.post_mime_type', 'image%', 'LIKE');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    $post_id = $row->getSourceProperty('ID');
    // Code deleted.
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
