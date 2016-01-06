<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Link field entity translation'),
  'description' => t('Mapping plugin for link fields, with entity translation.'),
  'class' => 'Drupal\mapping_field\MappingDestination\LinkFieldET',
];
