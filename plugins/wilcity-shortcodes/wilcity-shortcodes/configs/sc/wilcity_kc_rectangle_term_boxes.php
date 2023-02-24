<?php
return [
  'wilcity_kc_rectangle_term_boxes' => [
    'name'     => 'Rectangle Term Boxes',
    'group'    => 'term',
    'icon'     => 'sl-paper-plane',
    'css_box'  => true,
    'category' => WILCITY_SC_CATEGORY,
    'params'   => [
      'general' => [
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
        'number',
        'image_size',
        'is_show_parent_only',
        'is_hide_empty',
        'term_orderby',
        'order'
      ],
      'design'  => 'bootstrap_columns',
      'styling' => [
        [
          'name' => 'css_custom',
          'type' => 'css'
        ]
      ]
    ]
  ]
];
