<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Entityreference'),
  'description' => t('Mapping plugin for entityreference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Entityreference',
];
