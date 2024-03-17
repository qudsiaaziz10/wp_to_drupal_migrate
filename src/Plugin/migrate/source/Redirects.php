<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\migrate\Row;

/**
 * Provides a 'Redirects' migrate source.
 *
 * @MigrateSource(
 *  id = "wp_to_drupal_redirects"
 * )
 */
class Redirects extends WpToDrupalMigrateSourceBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('wp_posts', 'posts');
    $query->leftJoin('wp_term_relationships', 'terms', 'terms.object_id = posts.ID AND posts.post_type = \'post\'');
    $or_group = $query->orConditionGroup()
      ->condition('posts.post_type', parent::getProductTypes(), 'IN')
      ->condition('terms.term_taxonomy_id', [parent::getBlogTid(), parent::getRecipeTid()], 'IN');
    $query->fields('posts', ['ID', 'post_type', 'post_name', 'post_date', 'post_status'])
      ->distinct()
      ->condition($or_group)
      ->condition('posts.post_date', parent::getEarliestPostDate(), '>=')
      ->condition('posts.post_status', 'publish');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    // Code deleted.
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
