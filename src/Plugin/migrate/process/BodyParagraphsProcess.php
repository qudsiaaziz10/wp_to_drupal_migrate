<?php

namespace Drupal\wp_to_drupal_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Transforms value.
 *
 * @MigrateProcessPlugin(
 *     id="body_paragraphs_process"
 * )
 */
class BodyParagraphsProcess extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // Code deleted.
  }

}
