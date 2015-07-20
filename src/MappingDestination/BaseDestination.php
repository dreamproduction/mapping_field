<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 00:35
 */

namespace Drupal\mapping_field\MappingDestination;


abstract class BaseDestination {
  abstract function getForm($default_value, $states);

  abstract function setValue($wrapper, $value, $data);
}