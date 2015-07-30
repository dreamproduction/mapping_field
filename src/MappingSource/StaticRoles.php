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
    $options = $this->getDomainOptions();

    if (empty($options)) {
      return;
    }

    return[
      '#type' => 'checkboxes',
      '#title' => t('Select roles'),
      '#default_value' => $default_value,
      '#options' => $this->getRolesOptions(),
      '#states' => $states,
    ];
  }

  function getValue($row, $data) {
    $roles = $this->getRolesOptions();
    $selected_roles = [];
    foreach ($data as $rid => $selected) {
      if ($selected) {
        $selected_roles[$rid] = $roles[$rid];
      }
    }

    return $selected_roles;
  }

  protected function getRolesOptions() {
    return user_roles($membersonly = TRUE);
  }

}