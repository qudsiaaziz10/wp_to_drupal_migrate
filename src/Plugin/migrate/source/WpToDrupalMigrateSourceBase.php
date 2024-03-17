<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Provides a 'WpToDrupalMigrateSourceBase' migrate source.
 *
 * @MigrateSource(
 *  id = "wp_to_drupal_source_base",
 *  source_module = "wp_to_drupal_migrate"
 * )
 */
abstract class WpToDrupalMigrateSourceBase extends SqlBase {

  /**
  * Blog taxonomy id.
  *
  * @var int
  */
  const BLOG_TID = 1;

  /**
  * Course product type.
  *
  * @var int
  */
  const COURSE_PRODUCT_TYPE = 3;

  /**
  * Podcast product type.
  *
  * @var int
  */
  const PODCAST_PRODUCT_TYPE = 4;

  /**
  * Blog article type.
  *
  * @var int
  */
  const BLOG_ARTICLE_TYPE = 5;

  /**
  * Recipe article type.
  *
  * @var int
  */
  const RECIPE_ARTICLE_TYPE = 6;

  /**
  * Recipe taxonomy id.
  *
  * @var int
  */
  const RECIPE_TID = 2;

  /**
  * Earliest post date for the posts that should be migrated.
  *
  * @var string
  */
  const EARLIEST_POST_DATE = '2023-01-01';

  /**
  * Product types.
  *
  * @var array
  */
  const PRODUCT_TYPES = ['course', 'podcast'];

  /**
   * Process the WordPress post wysiwyg content.
   *
   * Note: This is prpbably ok for a one time migration.
   * However, a better way would be to write a process plugin instead.
   */
  public function processWysiwygContent(Row &$row) {
    $string = $row->getSourceProperty('post_content');
    // Replace [pullquote] with <blockquote>.
    $string = preg_replace('/\[pullquote[\s\S]*?\]/', '<blockquote>', $string);
    $string = preg_replace('/\[\/pullquote\]/', '</blockquote>', $string);

    // Code deleted.
  }

  /**
   * Processes the image alignment CSS classes.
   */
  public function processImageTag($matches) {
    // Code deleted.
  }

  /**
   * Processes caption.
   */
  public function processCaption($matches) {
    // Code deleted.
  }

  /**
   * Processes the src attribute.
   */
  public function processSrc($matches) {
    // Code deleted.
  }

  /**
   * Returns the new path.
   */
  public function processPath($path) {
    // Code deleted.
  }

  /**
   * Processes URLs.
   */
  public function processUrls($matches) {
    // Code deleted.
  }

  /**
   * Process href attributes.
   */
  public function processHref($matches) {
    // Code deleted.
  }

  /**
   * Gets the body content.
   */
  public function getBodyContentForBodyParagraphs($post_id) {
    // Code deleted.
  }

  /**
   * Gets the post image.
   */
  public function getImage($post_id) {
    // Code deleted.
  }

  /**
   * Sets meta.
   */
  public function setMeta(Row &$row) {
    $post_id = $row->getSourceProperty('ID');
    $query = $this->select('wp_postmeta', 'meta')
      ->condition('meta.post_id', $post_id)
      ->fields('meta');
    $post_meta = $query->execute()->fetchAll();
    $meta = [];
    foreach ($post_meta as $post_meta_row) {
      $meta[$post_meta_row['meta_key']] = $post_meta_row['meta_value'];
    }
    $row->setSourceProperty('meta', $meta);
  }

  /**
   * Set taxonomies.
   */
  public function setTaxonomies(Row &$row) {
    // Code deleted.
  }

  /**
   * Sets product type.
   */
  public function setProductType(Row &$row) {
    $type = $row->getSourceProperty('post_type');
    $product_type_map = $this->getProductTypeMap();
    $row->setSourceProperty('product_type', $product_type_map[$type]);
  }

  /**
   * Sets article type.
   */
  public function setArticleType(Row &$row) {
    $type = [0 => self::BLOG_ARTICLE_TYPE];
    $row->setSourceProperty('article_type', $type);
  }

  /**
   * Sets summary.
   */
  public function setSummary(Row &$row) {
    $summary = $row->getSourceProperty('post_excerpt');
    if (empty($summary)) {
      $post_id = $row->getSourceProperty('ID');
      $query = $this->select('wp_postmeta', 'meta')
        ->condition('meta.post_id', $post_id)
        ->condition('meta.meta_key', '_yoast_wpseo_metadesc')
        ->fields('meta');
      $meta = $query->execute()->fetch();
      $summary = $meta['meta_value'];
    }
    $row->setSourceProperty('summary', $summary);
  }

  /**
   * Sets status.
   */
  public function setStatus(Row &$row) {
    $post_status = $row->getSourceProperty('post_status');
    $status = $post_status == 'publish' ? 1 : 0;
    $row->setSourceProperty('status', $status);
  }

  /**
   * Sets publish date.
   */
  public function setPublishDate(Row &$row) {
    $publish_date = $row->getSourceProperty('post_date');
    $stamp = strtotime($publish_date);
    $row->setSourceProperty('publish_date', date('Y-m-d', $stamp));
  }

  /**
   * Sets author.
   */
  public function setAuthor(Row &$row) {
    // Code deleted.
  }

  /**
   * Gets product type.
   */
  public function getProductTypeMap() {
    return [
      'course' => self::COURSE_PRODUCT_TYPE,
      'podcast' => self::PODCAST_PRODUCT_TYPE,
    ];
  }

  /**
   * Gets article type.
   */
  public function getArticlesTypeMap() {
    return [
      'blog' => self::BLOG_ARTICLE_TYPE,
      'recipe' => self::RECIPE_ARTICLE_TYPE,
    ];
  }

  /**
   * Handles differences between entries in spreadsheet and WP.
   */
  public function getTaxonomyNamesMap() {
    return [
      'Products' => 'product',
      'Topics' => 'topic',
    ];
  }

  /**
   * Returns the WP Blog term taxonomy ID.
   */
  public function getBlogTid() {
    return self::BLOG_TID;
  }

  /**
   * Returns the WP Recipe term taxonomy ID.
   */
  public function getRecipeTid() {
    return self::RECIPE_TID;
  }

  /**
   * Returns the earliest post date.
   */
  public function getEarliestPostDate() {
    return self::EARLIEST_POST_DATE;
  }

  /**
   * Returns the post types that should be migrated as Product content type.
   */
  public function getProductTypes() {
    return self::PRODUCT_TYPES;
  }

}
