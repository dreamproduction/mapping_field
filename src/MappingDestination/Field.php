<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\Field
 */

namespace Drupal\mapping_field\MappingDestination;


class Field extends BaseDestination{

  protected $entity_type;

  protected $bundle;

  /**
   * Field constructor.
   * @param $entity_type
   * @param $bundle
   */
  public function __construct($entity_type, $bundle) {
    $this->setEntityType($entity_type);
    $this->setBundle($bundle);
  }

  function getForm($default_value = '_none', $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $options[$field_name] = $instance['label'];
      }
    }
    return [
      '#type' => 'select',
      '#title' => t('Destination'),
      '#options' => ['_none' => t('Select a field')] + $options,
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  /**
   * @return mixed
   */
  protected function getBundle() {
    return $this->bundle;
  }

  /**
   * @param mixed $bundle
   */
  protected function setBundle($bundle) {
    $this->bundle = $bundle;
  }

  /**
   * @return mixed
   */
  protected function getEntityType() {
    return $this->entity_type;
  }

  /**
   * @param mixed $entity_type
   */
  protected function setEntityType($entity_type) {
    $this->entity_type = $entity_type;
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

  function setValue($wrapper, $value, $field_name) {
    $wrapper->{$field_name}->set($value);
  }

}