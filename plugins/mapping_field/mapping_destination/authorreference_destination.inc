<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping destination: Author Reference'),
  'description' => t('Mapping plugin for authorreference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Authorreference',
];
