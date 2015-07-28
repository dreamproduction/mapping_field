<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Simple Entity Properties'),
  'description' => t('Mapping plugin for simple properties destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\SimpleProperty',
];
