<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Taxonomy Reference'),
  'description' => t('Mapping plugin for taxonomy reference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Taxonomyreference',
];
