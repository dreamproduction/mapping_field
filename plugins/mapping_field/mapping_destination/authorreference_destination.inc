<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Author Reference'),
  'description' => t('Mapping plugin for authorreference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Authorreference',
];
