<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\migrate\Row;

/**
 * Provides a 'Articles' migrate source.
 *
 * @MigrateSource(
 *  id = "wp_to_drupal_articles",
 *  source_module = "wp_to_drupal_migrate"
 * )
 */
class Articles extends WpToDrupalMigrateSourceBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('wp_posts', 'posts');
    $query->join('wp_term_relationships', 'terms', 'terms.object_id = posts.ID');
    $query->fields('posts')->distinct()
      ->condition('terms.term_taxonomy_id', [parent::getBlogTid(), parent::getRecipeTid()], 'IN')
      ->condition('posts.post_type', ['post'], 'IN')
      ->condition('posts.post_date', parent::getEarliestPostDate(), '>=');
    return $query;
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
  public function prepareRow(Row $row) {
    parent::prepareRow($row);
    $this->setMeta($row);
    $post_id = $row->getSourceProperty('ID');
    $body_content = $this->getBodyContentForBodyParagraphs($post_id);
    $row->setSourceProperty('body_content', $body_content);
    $image = $this->getImage($post_id);
    $row->setSourceProperty('image', $image);
    $this->setArticleType($row);
    $this->setSummary($row);
    $this->setTaxonomies($row);
    $this->setStatus($row);
    $this->setPublishDate($row);
    $this->setAuthor($row);
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
