<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Text with format'),
  'description' => t('Mapping plugin for text fields with format destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\TextFormat',
];
