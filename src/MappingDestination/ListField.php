<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\ListField
 */

namespace Drupal\mapping_field\MappingDestination;

class ListField extends SimpleField {

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    $value = trim($value);
    parent::setValue($wrapper, $value, $data);
  }

  protected function getSupportedFieldTypes() {
    return ['list_text', 'list_integer', 'list_float', 'list_boolean'];
  }

}