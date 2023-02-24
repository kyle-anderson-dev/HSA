<?php
global $post, $wilcityArgs;
if (empty($post->post_content)) {
    return '';
}

?>
<div class="content-box_module__333d9 <?php echo esc_attr(apply_filters('wilcity/filter/class-prefix',
    'wilcity-single-listing-content-box')); ?>">
    <?php get_template_part('single-listing/home-sections/section-heading'); ?>
    <div class="content-box_body__3tSRB">
        <div><?php the_content(); ?></div>
    </div>
</div>
