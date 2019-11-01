<?php
/**
 * Contains Drupal\mapping_field\MappingSource\BaseSource.
 */

namespace Drupal\mapping_field\MappingSource;


abstract class BaseSource {
  abstract public function getForm($default_value, $states);

  abstract public static function getValue($row, $data);
}
