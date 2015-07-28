<?php
/**
 * Contains Drupal\mapping_field\MappingDestination\BaseDestination;
 */

namespace Drupal\mapping_field\MappingDestination;


abstract class BaseDestination {
  abstract function getForm($default_value, $states);

  abstract function setValue($wrapper, $value, $data);

  abstract function getValue($wrapper, $data);

  abstract function addCondition(\EntityFieldQuery $efq, $data, $value);

  abstract function isIdField($data);

}