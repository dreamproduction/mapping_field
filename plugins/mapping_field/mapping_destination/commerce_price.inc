<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Commerce Price'),
  'description' => t('Mapping plugin for commerce price destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\CommercePrice',
];
