<?php

use Drupal\Core\Render\BubbleableMetadata;

function default_county_token_info() {
  $type = [
    'name' => t('Default County'),
    'description' => t(
      'Tokens related to individual content items, or "nodes".'
    ),
  ];

  $node['name_county'] = [
    'name' => t('County'),
    'description' => t('Get user county.'),
  ];

  return [
    'types' => ['default_county' => $type],
    'tokens' => ['default_county' => $node],
  ];
}

/**
 * @param $type
 * @param $tokens
 * @param array $data
 * @param array $options
 * @param BubbleableMetadata $bubbleable_metadata
 *
 * @return array
 */
function default_county_tokens(
  $type,
  $tokens,
  array $data,
  array $options,
  BubbleableMetadata $bubbleable_metadata
) {
  $replacements = [];

  if ($type === 'default_county') {
    foreach ($tokens as $name => $original) {
      if ($name === 'name_county') {
        $replacements[$original] = Drupal::service('default_county')
          ->getCounty();
      }
    }
  }

  return $replacements;
}
