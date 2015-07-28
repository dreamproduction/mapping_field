<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Simple Fields'),
  'description' => t('Mapping plugin for simple fields destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\SimpleField',
];
