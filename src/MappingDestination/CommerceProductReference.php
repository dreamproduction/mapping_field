<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 12/23/15
 * Time: 18:18
 */

namespace Drupal\mapping_field\MappingDestination;


class CommerceProductReference extends SimpleField {

  function getForm($default_value = ['reference_data' => '_none'], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $entity_type = 'commerce_product';

        $bundles = $this->getBundles();

        foreach ($bundles as $bundle => $bundle_label) {
          $bundle_options = $this->getBundleOptions($field_name, $entity_type, $bundle);

          // Parse through all bundle field options and add them as options.
          if (count($bundle_options)) {
            $option_key = $instance['label'] . ': ' . $bundle_label;
            $options[$option_key] = $bundle_options;
          }
        }
      }
    }

    return [
      'reference_data' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select the id field of the referenced entity.')] + $options,
        '#default_value' => $default_value['reference_data'],
        '#states' => $states,
      ],
    ];
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    list($field_name, $entity_type, $bundle, $ref_field_name) = explode('|', $data['reference_data']);
    $target_id = $this->getReferencedEntityId($entity_type, $bundle, $ref_field_name, $value);
    if ($target_id) {
      $wrapper->{$field_name}->set($target_id);
    }
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    list($field_name, , , $ref_field_name) = explode('|', $data['reference_data']);
    return (isset($wrapper->{$field_name}) && isset($wrapper->{$field_name}->{$ref_field_name})) ? $wrapper->{$field_name}->{$ref_field_name}->value() : NULL;
  }

  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['commerce_product_reference'];
  }

  /**
   * Gets the referenced entity for an entityreference field.
   *
   * @param string $entity_type
   * @param string $bundle
   * @param string $field_name This can be either an entity field or property.
   * @param mixed $value
   * @return mixed
   * @throws \EntityMalformedException
   */
  protected function getReferencedEntityId($entity_type, $bundle, $field_name, $value) {
    // Query to get the existing referenced entity,
    // based on the given field or property.
    $efq = new \EntityFieldQuery();
    $efq->entityCondition('entity_type', $entity_type);

    // Get the referenced entity properties.
    $entity_info = entity_get_property_info($entity_type);
    $referenced_entity_properties = $entity_info['properties'];
    // Check if the field name is actually a field or property,
    // and make the condition accordingly.
    if (isset($referenced_entity_properties[$field_name])){
      $efq->propertyCondition($field_name, $value);
    }else{
      $efq->fieldCondition($field_name, 'value', $value);
    }
    $result = $efq->execute();

    // If the query is successful, return the id of the first entity in the
    // result set.
    if (isset($result[$entity_type])) {
      $ids = array_keys($result[$entity_type]);
      return reset($ids);
    }
  }

  /**
   * Get all the target bundles for an commerce_product_reference field.
   *
   * @return array
   */
  protected function getBundles() {
    // Load the entity info for the target entity type.
    $entity_info = entity_get_info('commerce_product');
    foreach ($entity_info['bundles'] as $bundle_name => $bundle_info) {
      $bundles[$bundle_name] = $bundle_info['label'];
    }

    return $bundles;
  }

  /**
   * Get the target field and property options for one bundle.
   *
   * @param string $reference_field_name
   * @param string $entity_type
   * @param string $bundle
   * @return array
   */
  protected function getBundleOptions($reference_field_name, $entity_type, $bundle) {
    $options = [];

    // Get properties.
    $entity_info = entity_get_property_info($entity_type);
    $properties = $entity_info['properties'];
    foreach ($properties as $property_name => $property_info) {
      $wrapper = entity_metadata_wrapper($this->getEntityType());
      $property_info = isset($wrapper->{$property_name}) ? $wrapper->getPropertyInfo($property_name) : NULL;
      // Leave only the Integer properties for now.
      if (!empty($property_info) && in_array($property_info['type'], ['integer'])) {
        $key = implode('|', [$reference_field_name, $entity_type, $bundle, $property_name]);
        $options[$key] = $property_info['label'];
      }
    }

    // Get Field Instances.
    $instances = field_info_instances($entity_type, $bundle);
    foreach ($instances as $field_name => $instance) {
      $field = field_info_field($field_name);
      if (in_array($field['type'], ['text', 'number_integer', 'number_float', 'number_decimal'])) {
        $key = implode('|', [$reference_field_name, $entity_type, $bundle, $field_name]);
        $options[$key] = $instance['label'];
      }
    }
    return $options;
  }
}