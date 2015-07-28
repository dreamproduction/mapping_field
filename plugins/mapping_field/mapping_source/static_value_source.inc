<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_source plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingSource
 */

$plugin = [
  'title' => t('Static value'),
  'description' => t('Mapping plugin for using a static value as source.'),
  'class' => 'Drupal\mapping_field\MappingSource\StaticValue',
];
