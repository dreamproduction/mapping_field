<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/30/15
 * Time: 12:23
 */

namespace Drupal\mapping_field\MappingDestination;


class UserDomain extends BaseDestination {

  function getForm($default_value, $states) {
    return;
  }

  function setValue(\EntityMetadataWrapper $wrapper, $values, $data) {
    // If the entity type is not user, we have nothing to do here.
    if ($this->getEntityType() !== 'user') {
      return;
    }

    // If the value is a scalar, we map it to the expected format.
    if (is_scalar($values)) {
      $values = [$values => TRUE];
    }

    // If "Send to all affiliates" is checked, set all the domains, as the user
    // doesn't have this option.
    if (isset($values['-1']) && $values['-1']) {
      foreach ($values as $key => $value) {
        $values[$key] = TRUE;
      }
    }

    // Remove the "Send to all affiliates" option, this is invalid for user.
    if (isset($values['-1'])) {
      unset($values['-1']);
    }

    $user = $wrapper->value();
    $user->domain_user = $values;
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    // If the entity type is not user, we have nothing to do here.
    if ($this->getEntityType() !== 'user') {
      return;
    }

    $user = $wrapper->value();
    return $user->domain_user;
  }

  function addCondition(\EntityFieldQuery $efq, $data, $value) {
    return;
  }

  function isIdField($data) {
    return FALSE;
  }

}