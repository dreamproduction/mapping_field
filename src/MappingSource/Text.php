<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 11:39
 */

namespace Drupal\mapping_field\MappingSource;

class Text extends BaseSource{

  function getForm($default_value = '', $states) {
    return[
      '#type' => 'textfield',
      '#title' => t('Source'),
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  function getValue($row, $key) {
    return $row[$key];
  }
}