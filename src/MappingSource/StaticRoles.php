<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/30/15
 * Time: 19:12
 */

namespace Drupal\mapping_field\MappingSource;


class StaticRoles extends StaticValue {

  function getForm($default_value, $states) {
    return[
      '#type' => 'checkboxes',
      '#title' => t('Select roles'),
      '#default_value' => $default_value,
      '#options' => $this->getRolesOptions(),
      '#states' => $states,
    ];
  }

  public static function getValue($row, $data) {
    $roles = static::getRolesOptions();
    $selected_roles = [];
    foreach ($data as $rid => $selected) {
      if ($selected) {
        $selected_roles[$rid] = $roles[$rid];
      }
    }

    return $selected_roles;
  }

  protected static function getRolesOptions() {
    return user_roles($membersonly = TRUE);
  }

}
