<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_destination plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingDestination
 */

$plugin = [
  'title' => t('Commerce Product Reference'),
  'description' => t('Mapping plugin for product reference destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\CommerceProductReference',
];
