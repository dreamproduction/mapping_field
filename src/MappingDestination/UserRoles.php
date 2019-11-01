<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/30/15
 * Time: 17:33
 */

namespace Drupal\mapping_field\MappingDestination;


class UserRoles extends BaseDestination {

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
      $role_id = $values;
      $roles = $this->getRoles();
      if (isset($roles[$role_id])) {
        $values = [
          $role_id => $roles[$role_id],
          $this->getAuthenticatedRid() => $roles[$this->getAuthenticatedRid()]
        ];
      }
    }

    $user = $wrapper->value();
    $user->roles = $values;
  }

  function getValue(\EntityMetadataWrapper $wrapper, $data) {
    // If the entity type is not user, we have nothing to do here.
    if ($this->getEntityType() !== 'user') {
      return;
    }

    $user = $wrapper->value();
    return $user->roles;
  }

  function addCondition(\EntityFieldQuery $efq, $data, $value) {
    return;
  }

  function isIdField($data) {
    return FALSE;
  }

  protected function getRoles() {
    return user_roles($membersonly = TRUE);
  }

  protected function getAuthenticatedRid() {
    return DRUPAL_AUTHENTICATED_RID;
  }

}
