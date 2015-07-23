<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping destination: Properties'),
  'description' => t('Mapping plugin for properties destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Property',
];
