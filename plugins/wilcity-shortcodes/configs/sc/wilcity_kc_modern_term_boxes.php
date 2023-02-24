<?php
return [
  'wilcity_kc_modern_term_boxes' => [
    'name'     => esc_html__('Modern Term Boxes', 'wilcity-shortcodes'),
    'icon'     => 'sl-paper-plane',
    'group'    => 'term',
    'css_box'  => true,
    'category' => WILCITY_SC_CATEGORY,
    'params'   => [
      'general'         => [
        'heading',
        'heading_color',
        'desc',
        'desc_color',
        'header_desc_text_align',
        'term_redirect',
        'taxonomy_types',
        'listing_locations',
        'listing_cats',
        'listing_location',
        'listing_cat',
        'listing_tags',
        [
          'common'     => 'post_type',
          'additional' => [
            'description' => 'It is required if you are using Term Redirect: Search page Page.'
          ]
        ],
        'col_gap'             => 'col_gap',
        'number'              => 'number',
        'image_size'          => 'image_size',
        'is_show_parent_only' => 'is_show_parent_only',
        'is_hide_empty'       => 'is_hide_empty',
        'term_orderby'        => 'term_orderby',
        'order'               => 'order'
      ],
      'device settings' => 'bootstrap_columns',
      'styling'         => [
        [
          'name' => 'css_custom',
          'type' => 'css'
        ]
      ]
    ]
  ]
];
