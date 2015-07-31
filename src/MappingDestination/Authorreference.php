<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\Authorreference;
 */

namespace Drupal\mapping_field\MappingDestination;


class Authorreference extends SimpleProperty {

  function getForm($default_value = ['field_name' => '_none', 'is_id_field' => FALSE], $states) {
    $properties = $this->getProperties();

    $options = [];
    foreach ($properties as $property_name => $property_info) {
      $property_info = $this->getPropertyInfo($property_name);
      if (in_array($property_info['type'], $this->getSupportedPropertyTypes())) {
        $key = $property_info['label'];
        $options[$key] = $this->getExternalIdFieldOptions($property_name);
      }
    }

    if (!count($options)) {
      return;
    }

    return [
      'reference_data' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select the external id field of the referenced user.')] + $options,
        '#default_value' => $default_value['reference_data'],
        '#states' => $states,
      ],
    ];
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    if (!isset($data['reference_data']) || $data['reference_data'] == '_none') {
      return;
    }
    list($property_name, $entity_type, $bundle, $ref_field_name) = explode('|', $data['reference_data']);
    $uid = $this->getReferencedEntityId($entity_type, $bundle, $ref_field_name, $value);
    $wrapper->{$property_name}->set($uid);
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    if (!isset($data['reference_data']) || $data['reference_data'] == '_none') {
      return;
    }
    list($property_name, , , $ref_field_name) = explode('|', $data['reference_data']);
    return (isset($wrapper->{$property_name}) && isset($wrapper->{$property_name}->{$ref_field_name})) ? $wrapper->{$property_name}->{$ref_field_name}->value() : NULL;
  }

  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedPropertyTypes() {
    return ['user'];
  }

  /**
   * Gets the referenced entity for an entityreference field.
   *
   * @param string $entity_type
   * @param string $bundle
   * @param string $field_name
   * @param mixed $value
   * @return mixed
   * @throws \EntityMalformedException
   */
  protected function getReferencedEntityId($entity_type, $bundle, $field_name, $value) {
    // Query to get the existing referenced entity.
    $efq = new \EntityFieldQuery();
    $result = $efq->entityCondition('entity_type', $entity_type)
      ->entityCondition('bundle', $bundle)
      ->fieldCondition($field_name, 'value', $value)
      ->execute();

    // If the query is successful, return the id of the first entity in the
    // result set.
    if (isset($result[$entity_type])) {
      $ids = array_keys($result[$entity_type]);
      return reset($ids);
    }

    // Otherwise, create a user with the required external id.
    $entity = $this->createReferencedEntity($entity_type, $bundle, $field_name, $value);
    list($id, , ) = entity_extract_ids($entity_type, $entity);

    return $id;
  }

  /**
   * Create and return a target entity for a reference field.
   *
   * @param string $entity_type
   * @param string $bundle
   * @param string $field_name
   * @param $value
   * @return mixed
   */
  protected function createReferencedEntity($entity_type, $bundle, $field_name, $value) {
    $properties = [];

    // Create the entity.
    $entity = entity_create($entity_type, $properties);

    // Get a metadata wrapper for the entity.
    $wrapper = entity_metadata_wrapper($entity_type, $entity);

    // Set the id field's value.
    $wrapper->{$field_name}->set($value);

    // Save the entity.
    $wrapper->save();

    return $entity;
  }

  /**
   * Get the target field options for one bundle.
   *
   * @param string $property_name
   * @return array
   */
  protected function getExternalIdFieldOptions($property_name) {
    $entity_type = $bundle = 'user';
    $options = [];
    $instances = field_info_instances($entity_type, $bundle);
    foreach ($instances as $field_name => $instance) {
      $field = field_info_field($field_name);
      if (in_array($field['type'], ['text', 'number_integer', 'number_float', 'number_decimal'])) {
        $key = implode('|', [$property_name, $entity_type, $bundle, $field_name]);
        $options[$key] = $instance['label'];
      }
    }
    return $options;
  }
}