<?php
/**
 * Contains Drupal\mapping_field\MappingSource\StaticValue
 */

namespace Drupal\mapping_field\MappingSource;


class StaticValue extends BaseSource {

  function getForm($default_value = '', $states) {
    return[
      '#type' => 'textfield',
      '#title' => t('Static value'),
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  function getValue($row, $key, $import_file) {
    return $key;
  }

}