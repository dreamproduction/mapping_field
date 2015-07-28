<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_source plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingSource
 */

$plugin = [
  'title' => t('Freetext excel mapping'),
  'description' => t('Mapping plugin for selecting the source column by typing the column name.'),
  'class' => 'Drupal\mapping_field\MappingSource\FreetextColumn',
];
