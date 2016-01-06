<?php
/**
 * Contains Drupal\mapping_field\MappingSource\BaseSource.
 */

namespace Drupal\mapping_field\MappingSource;


abstract class BaseSource {
  abstract function getForm($default_value, $states);

  abstract function getValue($row, $data, $import_file);
}