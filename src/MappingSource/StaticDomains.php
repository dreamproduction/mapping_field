<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/30/15
 * Time: 13:25
 */

namespace Drupal\mapping_field\MappingSource;


class StaticDomains extends StaticValue {

  function getForm($default_value, $states) {
    $options = $this->getDomainOptions();

    if (empty($options)) {
      return;
    }

    return[
      '#type' => 'checkboxes',
      '#title' => t('Select domains'),
      '#options' => ['-1' => t('Send to all affiliates')] + $this->getDomainOptions(),
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  protected function getDomainOptions() {
    $options = [];
    foreach ($this->getDomains() as $domain) {
      // The domain must be valid.
      if ($domain['valid']) {
        $options[$domain['domain_id']] = $this->checkPlain($domain['sitename']);
      }
    }
    return $options;
  }

  protected function getDomains() {
    if (!function_exists('domain_domains')) {
      return [];
    }
    return domain_domains();
  }

  protected function checkPlain($text) {
    return check_plain($text);
  }
}