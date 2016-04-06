<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 12/23/15
 * Time: 17:19
 */

namespace Drupal\mapping_field\MappingDestination;


class LinkFieldET extends LinkField {

  function getForm($default_value = ['field_name' => '_none', 'column' => 'url', 'language' => LANGUAGE_NONE], $states) {
    $form = parent::getForm($default_value, $states);

    $language_options = [
      LANGUAGE_NONE => t("None")
    ];
    $languages = language_list();
    foreach ($languages as $langcode => $language) {
      $language_options[$langcode] = $language->native;
    }

    $form['language'] = [
      '#type' => 'select',
      '#title' => t('Language'),
      '#options' => $language_options,
      '#default_value' => $default_value['language'],
      '#states' => $states,
    ];

    return $form;
  }

  function setValue(\EntityMetadataWrapper $wrapper, $value, $data) {
    $current_language = $wrapper->getPropertyLanguage() ?: LANGUAGE_NONE;
    $wrapper->language($data['language']);
    parent::setValue($wrapper, $value, $data);
    $wrapper->language($current_language);
  }

}