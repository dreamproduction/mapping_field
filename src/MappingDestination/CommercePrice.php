<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\CommercePrice
 */

namespace Drupal\mapping_field\MappingDestination;

class CommercePrice extends BaseDestination {

  function getForm($default_value = ['field_name' => '_none'], $states) {
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
      ]
    ];
  }

  /**
   * @param \EntityMetadataWrapper $wrapper
   * @param $value
   * @param $data
   */
  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    $value = commerce_currency_decimal_to_amount($value, $this->getCurrency());
    $field_name = $data['field_name'];
    $price_array = [
      'amount' => $value,
      'currency_code' => $this->getCurrency()
    ];
    $price_array['data'] = commerce_price_component_add($price_array, 'base_price', $price_array, TRUE);
    $wrapper->{$field_name}->set($price_array);
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    if (!isset($wrapper->{$data['field_name']})) {
      return;
    }
    $amount = $wrapper->{$data['field_name']}->amount->value();
    return commerce_currency_amount_to_decimal($amount, $this->getCurrency());
  }

  function isIdField($data) {
    return $data['is_id_field'];
  }

  function addCondition(\EntityFieldQuery $efq, $data, $value) {
    return;
  }

  protected function getFields() {
    return field_info_instances($this->getEntityType(), $this->getBundle());
  }

  protected function getFieldInfo($field_name) {
    return field_info_field($field_name);
  }

  protected function getSupportedFieldTypes() {
    return ['commerce_price'];
  }

  private function getCurrency() {
    return  commerce_default_currency();
  }

}

