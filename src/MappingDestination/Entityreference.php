<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\Entityreference;
 */

namespace Drupal\mapping_field\MappingDestination;


class Entityreference extends SimpleField {

  function getForm($default_value = ['reference_data' => '_none'], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $field_info = field_info_field($field_name);
        $entity_type = $field_info['settings']['target_type'];

        $bundles = $this->getBundles($field_name);

        foreach ($bundles as $bundle => $bundle_label) {
          $bundle_options = $this->getBundleOptions($field_name, $entity_type, $bundle);

          if (count($bundle_options)) {
            $key = $instance['label'] . ': ' . $bundle_label;
            $options[$key] = $bundle_options;
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
    $wrapper->{$field_name}->set($target_id);
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    list($field_name, , , $ref_field_name) = explode('|', $data['reference_data']);
    return (isset($wrapper->{$field_name}) && isset($wrapper->{$field_name}->{$ref_field_name})) ? $wrapper->{$field_name}->{$ref_field_name}->value() : NULL;
  }

  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['entityreference'];
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

    // Otherwise, create an entity with the required external id.
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

    // Set the bundle property, if it exists.
    $bundle_key = $this->getBundleKey($entity_type);
    if ($bundle_key) {
      $properties[$bundle_key] = $bundle;
    }

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
   * Get the bundle key for a specific entity type.
   *
   * @param string $entity_type
   * @return string
   */
  protected function getBundleKey($entity_type) {
    $entity_info = entity_get_info($entity_type);
    return isset($entity_info['entity keys']['bundle']) ? $entity_info['entity keys']['bundle'] : NULL;
  }

  /**
   * Get all the target bundles for an entityreference field.
   *
   * @param string $field_name
   * @return array
   */
  protected function getBundles($field_name) {
    // Load field info.
    $field_info = field_info_field($field_name);

    // Get the target entity type.
    $entity_type = $field_info['settings']['target_type'];

    // Load the entity info for the target entity type.
    $entity_info = entity_get_info($entity_type);

    // If the handler type is "base", the target bundles are stored as settings
    // on the field info, we get them from there.
    if (isset($field_info['settings']['handler']) && $field_info['settings']['handler'] == 'base') {
      foreach ($field_info['settings']['handler_settings']['target_bundles'] as $bundle_name) {
        $bundles[$bundle_name] = $entity_info['bundles'][$bundle_name]['label'];
      }
    }

    // If we have no targer bundles set, it means all the bundles can be target
    // bundles.
    if (empty($bundles)) {
      foreach ($entity_info['bundles'] as $bundle_name => $bundle_info) {
        $bundles[$bundle_name] = $bundle_info['label'];
      }
    }

    return $bundles;
  }

  /**
   * Get the target field options for one bundle.
   *
   * @param string $reference_field_name
   * @param string $entity_type
   * @param string $bundle
   * @return array
   */
  protected function getBundleOptions($reference_field_name, $entity_type, $bundle) {
    $options = [];
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