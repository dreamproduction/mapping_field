<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/28/15
 * Time: 18:13
 */

namespace Drupal\mapping_field\MappingDestination;


class TextFormat extends SimpleField {

  function getForm($default_value = ['field_name' => '_none', 'format' => 'plain_text'], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $options[$field_name] = $instance['label'];
      }
    }

    $formats = $this->getFilterFormats();
    $format_options = [];

    foreach ($formats as $format_machine_name => $format) {
      $format_options[$format_machine_name] = $format->name;
    }

    return [
      'field_name' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select a field')] + $options,
        '#default_value' => $default_value['field_name'],
        '#states' => $states,
      ],
      'format' => [
        '#type' => 'select',
        '#title' => t('Format'),
        '#options' => $format_options,
        '#default_value' => $default_value['format'],
        '#states' => $states,
      ]
    ];
  }

  function setValue($wrapper, $value, $data) {
    $field_name = $data['field_name'];
    $wrapper->{$field_name}->set(['value' => $value, 'format' => $data['format']);
  }


  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['text_long'];
  }

  protected function getFilterFormats() {
    return filter_formats();
  }

}