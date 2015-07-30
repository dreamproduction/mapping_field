<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\SimpleField
 */

namespace Drupal\mapping_field\MappingDestination;

class SimpleField extends BaseDestination {

  function getForm($default_value = ['field_name' => '_none', 'is_id_field' => FALSE], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $options[$field_name] = $instance['label'];
      }
    }
    return [
      'field_name' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select a field')] + $options,
        '#default_value' => $default_value['field_name'],
        '#states' => $states,
      ],
      'is_id_field' => [
        '#type' => 'checkbox',
        '#title' => t('Is ID field'),
        '#default_value' => $default_value['is_id_field'],
        '#states' => $states,
      ]
    ];
  }

  function setValue($wrapper, $value, $data) {
    $field_name = $data['field_name'];
    $wrapper->{$field_name}->set($value);
  }

  function getValue($wrapper, $data) {
    return isset($wrapper->{$data['field_name']}) ? $wrapper->{$data['field_name']}->value() : NULL;
  }

  function isIdField($data) {
    return $data['is_id_field'];
  }

  function addCondition(\EntityFieldQuery $efq, $data, $value){
    $efq->fieldCondition($data['field_name'], 'value', $value);
  }

  protected function getFields() {
    return field_info_instances($this->getEntityType(), $this->getBundle());
  }

  protected function getFieldInfo($field_name) {
    return field_info_field($field_name);
  }

  protected function getSupportedFieldTypes() {
    return ['text', 'number_integer', 'number_float', 'number_decimal'];
  }

}