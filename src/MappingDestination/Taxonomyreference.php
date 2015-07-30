<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\Taxonomyreference;
 */

namespace Drupal\mapping_field\MappingDestination;


class Taxonomyreference extends SimpleField {

  function getForm($default_value = ['field_name' => '_none', 'is_id_field' => FALSE], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);

      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $vocabulary = $this->getVocabulary($field_name);

        $vocabulary_field_options = $this->getVocabularyFieldOptions($field_name, $vocabulary);

        if (count($vocabulary_field_options)) {
          $key = $instance['label'] . ': ' . $vocabulary->name;
          $options[$key] = $vocabulary_field_options;
        }
      }
    }

    return [
      'reference_data' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select the id field of the referenced taxonomy.')] + $options,
        '#default_value' => $default_value['reference_data'],
        '#states' => $states,
      ],
    ];
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    list($field_name, $entity_type, $vocabulary_name, $ref_field_name) = explode('|', $data['reference_data']);
    $term_id = $this->getReferencedTermId($entity_type, $vocabulary_name, $ref_field_name, $value);
    $wrapper->{$field_name}->set($term_id);
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    list($field_name, , , $ref_field_name) = explode('|', $data['reference_data']);
    return (isset($wrapper->{$field_name}->{$ref_field_name}) && !empty($wrapper->{$field_name}->value())) ? $wrapper->{$field_name}->{$ref_field_name}->value() : NULL;
  }

  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['taxonomy_term_reference'];
  }

  /**
   * Gets the referenced term for an taxonomy reference field.
   *
   * @param string $entity_type
   * @param string $vocabulary_name
   * @param string $field_name
   * @param mixed $value
   * @return mixed
   * @throws \EntityMalformedException
   */
  protected function getReferencedTermId($entity_type, $vocabulary_name, $field_name, $value) {
    // Query to get the existing referenced entity.
    $efq = new \EntityFieldQuery();
    $result = $efq->entityCondition('entity_type', $entity_type)
      ->entityCondition('bundle', $vocabulary_name)
      ->fieldCondition($field_name, 'value', $value)
      ->execute();

    // If the query is successful, return the id of the first term in the
    // result set.
    if (isset($result[$entity_type])) {
      $ids = array_keys($result[$entity_type]);
      return reset($ids);
    }

    // Otherwise, create a taxonomy term with the required external id.
    $entity = $this->createReferencedTerm($entity_type, $vocabulary_name, $field_name, $value);
    list($id, , ) = entity_extract_ids($entity_type, $entity);

    return $id;
  }

  /**
   * Create and return a term for a reference field.
   *
   * @param string $entity_type
   * @param string $vocabulary_name
   * @param string $field_name
   * @param $value
   * @return mixed
   */
  protected function createReferencedTerm($entity_type, $vocabulary_name, $field_name, $value) {
    $properties = [];

    // Set the bundle property, if it exists.
    $bundle_key = $this->getBundleKey($entity_type);
    if ($bundle_key) {
      $properties[$bundle_key] = $vocabulary_name;
      // Load the vocabulary.
      $vocabulary = taxonomy_vocabulary_machine_name_load($vocabulary_name);
      $properties['vid'] = $vocabulary->vid;
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
   * Get all the target vocabulary for a taxonomyreference field.
   *
   * @param string $field_name
   * @return array
   */
  protected function getVocabulary($field_name) {
    // Load field info.
    $field_info = field_info_field($field_name);

    // Get the target vocabulary.
    $vocabulary_name = $field_info['settings']['allowed_values'][0]['vocabulary'];

    // Load the vocabulary.
    $vocabulary = taxonomy_vocabulary_machine_name_load($vocabulary_name);

    return $vocabulary;
  }

  /**
   * Get the target field options for one Vocabulary.
   *
   * @param string $reference_field_name
   * @param object $vocabulary
   * @return array
   */
  protected function getVocabularyFieldOptions($reference_field_name, $vocabulary) {
    $entity_type = 'taxonomy_term';
    $bundle = $vocabulary->machine_name;
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