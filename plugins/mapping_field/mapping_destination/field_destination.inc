<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = array(
  'title' => t('Mapping destination: Fields'),
  'description' => t('Mapping plugin for fields destination.'),
  'class' => 'Drupal\mapping_field\MappingDestination\Field',
);
