<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('User roles'),
  'description' => t('Mapping plugin for user roles destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\UserRoles',
];
