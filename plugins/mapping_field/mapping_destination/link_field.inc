<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Link field'),
  'description' => t('Mapping plugin for link fields.'),
  'class' => 'Drupal\mapping_field\MappingDestination\LinkField',
];
