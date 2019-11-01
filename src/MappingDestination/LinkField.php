<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 12/23/15
 * Time: 14:33
 */

namespace Drupal\mapping_field\MappingDestination;


class LinkField extends SimpleField {

  function getForm($default_value = ['field_name' => '_none', 'column' => 'url'], $states) {
    $instances = $this->getFields();
    $options = [];

    foreach ($instances as $field_name => $instance) {
      $field = $this->getFieldInfo($field_name);
      if ($field['cardinality'] == 1 && in_array($field['type'], $this->getSupportedFieldTypes())) {
        $options[$field_name] = $instance['label'];
      }
    }

    $column_options = ['title' => t('Title'), 'url' => t('Url')];

    return [
      'field_name' => [
        '#type' => 'select',
        '#title' => t('Destination'),
        '#options' => ['_none' => t('Select a field')] + $options,
        '#default_value' => $default_value['field_name'],
        '#states' => $states,
      ],
      'column' => [
        '#type' => 'select',
        '#title' => t('Column'),
        '#options' => $column_options,
        '#default_value' => $default_value['column'],
        '#states' => $states,
      ]
    ];
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    $field_name = $data['field_name'];
    $encoded_value = $this->urlencode($value);
    $wrapper->{$field_name}->{$data['column']}->set($encoded_value);
  }


  function isIdField($data) {
    return FALSE;
  }

  protected function getSupportedFieldTypes() {
    return ['link_field'];
  }

  /**
   * @param string $url
   * @return string
   */
  private function urlEncode($url) {
    $decoded = $this->urlDecode($url);
    $parsed_url = parse_url($decoded);
    return $this->urlAssemble($parsed_url);
  }

  private function urlDecode($value) {
    $decoded = urldecode($value);
    while ($decoded != $value) {
      $value = $decoded;
      $decoded = urldecode($value);
    }
    return $value;
  }

  private function urlAssemble($parsed_url) {
    $return = '';

    // Add the scheme.
    if (isset($parsed_url['scheme'])) {
      $return .= rawurlencode($parsed_url['scheme']) . '://';
    }
    elseif (isset($parsed_url['host'])) {
      $return .= '//';
    }

    // Add the user.
    if (isset($parsed_url['user'])) {
      $return .= rawurlencode($parsed_url['user']);
    }

    // Add the pass.
    if (isset($parsed_url['pass'])) {
      $return .= ':' . rawurlencode($parsed_url['pass']);
    }

    // If user or pass, add @.
    if (isset($parsed_url['user']) || isset($parsed_url['pass'])) {
      $return .= '@';
    }

    // Add the host.
    if (isset($parsed_url['host'])) {
      $return .= rawurlencode($parsed_url['host']);
    }

    // Add the port.
    if (isset($parsed_url['port'])) {
      $return .= ':' . rawurlencode($parsed_url['port']);
    }

    // Add the path.
    if (isset($parsed_url['path'])) {
      $path_parts = explode('/', $parsed_url['path']);
      foreach ($path_parts as $key => $path_part) {
        $path_parts[$key] = rawurlencode($path_part);
      }
      $return .= implode('/', $path_parts);
    }

    // Add the query.
    if (isset($parsed_url['query'])) {
      parse_str($parsed_url['query'], $query);
      $return .= '?' . http_build_query($query);
    }

    // Add the fragment.
    if (isset($parsed_url['fragment'])) {
      $return .= '#' . urlencode($parsed_url['fragment']);
    }

    return $return;
  }

}