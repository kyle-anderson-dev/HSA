<?php

use WilokeListingTools\Framework\Helpers\General;


$aAtts =  [
    'ajax_action' => 'wiloke_fetch_posts',
    'post_types'  => implode(',', General::getPostTypeKeys(false)),
    'post_status' => 'publish'
];

if (isset($_GET['post'])) {
    if (get_post_status($_GET['post']) == 'publish') {
        $aAtts['disabled'] = 'disabled';
    }
}

return [
    'promotion_information' => [
        'id'           => 'promotion_information',
        'title'        => 'Promotion Information',
        'object_types' => ['promotion'],
        'context'      => 'normal',
        'priority'     => 'low',
        'show_names'   => true, // Show field names on the left
        'fields'       => [
            [
                'type'        => 'select2_posts',
                'id'          => 'wilcity_listing_id',
                'show_link'   => true,
                'name'        => 'Listing Name',
                'description' => esc_html__('Warning: After the promotion is being published, You CAN NOT change to another Listing',
                    'wiloke-listing-tools'),
                'attributes'  => $aAtts
            ]
        ]
    ],
];
