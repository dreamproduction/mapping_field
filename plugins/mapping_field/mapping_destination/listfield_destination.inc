<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('List Fields'),
  'description' => t('Mapping plugin for list fields destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\ListField',
];
