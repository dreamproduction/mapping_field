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

  public static function getValue($row, $key) {
    return $row[$key];
  }
}
