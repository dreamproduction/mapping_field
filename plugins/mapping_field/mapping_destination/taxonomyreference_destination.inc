<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping destination: Taxonomy Reference'),
  'description' => t('Mapping plugin for taxonomy reference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Taxonomyreference',
];
