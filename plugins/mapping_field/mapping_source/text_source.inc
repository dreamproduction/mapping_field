<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping source: Freetext'),
  'description' => t('Mapping plugin for freetext source.'),
  'class' => 'Drupal\mapping_field\MappingSource\Text',
];
