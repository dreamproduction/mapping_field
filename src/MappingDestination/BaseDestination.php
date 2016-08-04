<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\BaseDestination;
 */

namespace Drupal\mapping_field\MappingDestination;


abstract class BaseDestination {

  /**
   * @var string
   */
  protected $entity_type;

  /**
   * @var string
   */
  protected $bundle;

  /**
   * BaseDestination constructor.
   * @param $entity_type
   * @param $bundle
   */
  public function __construct($entity_type, $bundle) {
    $this->setEntityType($entity_type);
    $this->setBundle($bundle);
  }

  abstract function getForm($default_value, $states);

  abstract function setValue(\EntityMetadataWrapper $wrapper, $value, $data);

  abstract function getValue(\EntityMetadataWrapper $wrapper, $data);

  abstract function addCondition(\EntityFieldQuery $efq, $data, $value, $operator);

  abstract function isIdField($data);

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

}