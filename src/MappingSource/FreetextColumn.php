<?php
/**
 * Contains Drupal\mapping_field\MappingSource\FreetextColumn.
 */

namespace Drupal\mapping_field\MappingSource;

class FreetextColumn extends BaseSource {

  function getForm($default_value = '', $states) {
    return[
      '#type' => 'textfield',
      '#title' => t('Source'),
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  function getValue($row, $key, $import_file) {
    return $row[$key];
  }
}