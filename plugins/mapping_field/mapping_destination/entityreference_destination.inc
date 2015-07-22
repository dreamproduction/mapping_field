<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping destination: Entityreference'),
  'description' => t('Mapping plugin for entityreference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Entityreference',
];
