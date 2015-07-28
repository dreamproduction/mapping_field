<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\SimpleProperty
 */

namespace Drupal\mapping_field\MappingDestination;

class SimpleProperty extends BaseDestination {

  protected $entity_type;

  protected $bundle;

  /**
   * SimpleProperty constructor.
   * @param $entity_type
   * @param $bundle
   */
  public function __construct($entity_type, $bundle) {
    $this->setEntityType($entity_type);
    $this->setBundle($bundle);
  }

  function getForm($default_value = ['property_name' => '_none', 'is_id_field' => FALSE], $states) {
    $properties = $this->getProperties();
    $options = [];
    foreach ($properties as $property_name => $property_info) {
      $property_info = $this->getPropertyInfo($property_name);
      if (in_array($property_info['type'], $this->getSupportedPropertyTypes())) {
        $options[$property_name] = $property_info['label'];
      }
    }
    return [
      'property_name' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select a property')] + $options,
        '#default_value' => $default_value['property_name'],
        '#states' => $states,
      ],
      'is_id_property' => [
        '#type' => 'checkbox',
        '#title' => t('Is ID Property'),
        '#default_value' => $default_value['is_id_property'],
        '#states' => $states,
      ]
    ];
  }

  function setValue($wrapper, $value, $data) {
    $property_name = $data['property_name'];
    $wrapper->{$property_name}->set($value);
  }

  function getValue($wrapper, $data) {
    return isset($wrapper->{$data['property_name']}) ? $wrapper->{$data['property_name']}->value() : NULL;
  }

  function isIdField($data) {
    return $data['is_id_property'];
  }

  function addCondition(\EntityFieldQuery $efq, $data, $value){
    $efq->propertyCondition($data['property_name'], $value);
  }

  protected function getProperties() {
    $entity_info = entity_get_property_info($this->getEntityType());
    return $entity_info['properties'];
  }

  protected function getPropertyInfo($property_name) {
    $wrapper = entity_metadata_wrapper($this->getEntityType());
    return isset($wrapper->{$property_name}) ? $wrapper->getPropertyInfo($property_name) : NULL;
  }

  protected function getSupportedPropertyTypes() {
    return ['text', 'integer', 'positive_integer', 'decimal', 'token', 'date'];
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
}