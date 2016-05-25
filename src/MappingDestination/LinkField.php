<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 12/23/15
 * Time: 14:33
 */

namespace Drupal\mapping_field\MappingDestination;


class LinkField extends SimpleField {

  function getForm($default_value = ['field_name' => '_none', 'column' => 'url'], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $options[$field_name] = $instance['label'];
      }
    }

    $column_options = ['title' => t('Title'), 'url' => t('Url')];

    return [
      'field_name' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select a field')] + $options,
        '#default_value' => $default_value['field_name'],
        '#states' => $states,
      ],
      'column' => [
        '#type' => 'select',
        '#title' => t('Column'),
        '#options' => $column_options,
        '#default_value' => $default_value['column'],
        '#states' => $states,
      ]
    ];
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    $field_name = $data['field_name'];
    $encoded_value = $this->urlencode($value);
    $wrapper->{$field_name}->{$data['column']}->set($encoded_value);
  }


  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['link_field'];
  }

  private function urlencode($value) {
    $decoded = urldecode($value);
    while ($decoded != $value) {
      $value = $decoded;
      $decoded = urldecode($value);
    }
    return urlencode($value);
  }

}