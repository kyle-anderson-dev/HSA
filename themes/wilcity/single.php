<?php
$aEventGroup = \WilokeListingTools\Framework\Helpers\General::getPostTypeKeysGroup('event');
$aListingGroup = \WilokeListingTools\Framework\Helpers\General::getPostTypeKeysGroup('listing');

if (is_singular('elementor_library')) {
    get_template_part('templates/page-builder');
} else if ( !empty($aListingGroup) && is_singular($aListingGroup)) {
    get_template_part('post-types/listing');
} else if ( !empty($aEventGroup) && is_singular($aEventGroup)) {
    get_template_part('post-types/event');
} else {
    get_template_part('post-types/post');
}
