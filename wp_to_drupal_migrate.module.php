<?php

/**
 * @file
 * Contains wp_to_drupal_migrate.module.
 */

use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Implements hook_help().
 */
function wp_to_drupal_migrate_help($route_name, RouteMatchInterface $route_match) {
  switch ($route_name) {
    // Help for the wp_to_drupal_migrate module.
    case 'help.page.wp_to_drupal_migrate':
      $output = '';
      $output .= '<h3>' . t('About') . '</h3>';
      $output .= '<p>' . t('Example WordPress to Drupal Migration Code') . '</p>';
      return $output;

    default:
  }
}
