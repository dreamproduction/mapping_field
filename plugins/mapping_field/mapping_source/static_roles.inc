<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_source plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingSource
 */

$plugin = [
  'title' => t('Static roles'),
  'description' => t('Mapping plugin for using static roles as source.'),
  'class' => 'Drupal\mapping_field\MappingSource\StaticRoles',
];
