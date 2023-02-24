<?php
global $post, $wiloke, $wilcityGallerySettings;
$type = isset($wiloke->aThemeOptions['listing_template']) && !empty($wiloke->aThemeOptions['listing_template']) ?
    $wiloke->aThemeOptions['listing_template'] :
    'featured_image_fullwidth';

$type = apply_filters(
    'wilcity/filter/single-listing/header/type',
    $type,
    $post
);

if ($type == 'slider') {
    $wilcityGallerySettings = \WilokeListingTools\Framework\Helpers\GetSettings::getPostMeta($post->ID, 'gallery');
    if (empty($wilcityGallerySettings)) {
        $type = 'featured_image_fullwidth';
    }
}

switch ($type) {
    case 'slider':
        get_template_part('single-listing/header-slider');
        break;
    case 'featured_image_fullwidth':
        get_template_part('single-listing/header-featuredimage-fullwidth');
        break;
    default:
        do_action('wilcity/single-listing/header/' . $type, $post, $wiloke);
        break;
}
