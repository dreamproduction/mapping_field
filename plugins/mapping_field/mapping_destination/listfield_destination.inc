<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping destination: ListField'),
  'description' => t('Mapping plugin for list fields destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\ListField',
];
