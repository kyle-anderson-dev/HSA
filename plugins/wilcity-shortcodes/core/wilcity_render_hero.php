<?php

use WilokeListingTools\Framework\Helpers\GetSettings;
use WILCITY_SC\SCHelpers;
use WilokeListingTools\Framework\Helpers\TermSetting;

global $wilcityEnqueueHero;
$wilcityEnqueueHero = false;

function wilcity_hero_render_list_of_terms_suggestion($oTerm)
{
	$aIcon = WilokeHelpers::getTermOriginalIcon($oTerm, false);
	$termLink = get_term_link($oTerm);
	?>
    <a class="hero_highlightItem__DZRDM" href="<?php echo esc_url($termLink); ?>">
		<?php if (isset($aIcon['icon'])) : ?>
            <i class="<?php echo esc_attr($aIcon['icon']); ?>"
               style="color: <?php echo esc_attr($aIcon['color']); ?>"></i>
		<?php elseif (isset($aIcon['url'])): ?>
            <img src="<?php echo esc_attr($aIcon['url']); ?>" alt="<?php echo esc_attr($oTerm->name); ?>">
		<?php endif; ?>
        <span><?php echo esc_html($oTerm->name); ?></span>
    </a>
	<?php
}

function wilcity_sc_hero_search_form($aAtts)
{
	if ($aAtts['toggle_list_of_suggestions'] == 'enable') :
		global $wilcityEnqueueHero;

		if (!$wilcityEnqueueHero) {
			wp_enqueue_script('HeroSearchForm');
			$wilcityEnqueueHero = true;
		}

		?>
        <div class="hero_highlight__1JwX3">
            <div class="highlight-lava-js">
				<?php
				if ($aAtts['orderby'] == 'specify_terms') {
					$termKey = $aAtts['taxonomy'] . 's';
					$taxIds = $aAtts[$termKey];

					$aTermIds = SCHelpers::getAutoCompleteVal($taxIds);
					foreach ($aTermIds as $termID) {
						$oTerm = GetSettings::getTermBy('term_id', trim($termID), $aAtts['taxonomy']);
						if (!empty($oTerm) && !is_wp_error($oTerm)) {
							wilcity_hero_render_list_of_terms_suggestion($oTerm);
						}
					}
				} else {
					$aArgs = [
						'taxonomy'   => $aAtts['taxonomy'],
						'number'     => $aAtts['maximum_terms_suggestion'],
						'orderby'    => $aAtts['orderby'],
						'hide_empty' => false
					];

					$aTerms = GetSettings::getTerms($aArgs);
					if (!empty($aTerms) && !is_wp_error($aTerms)) {
						foreach ($aTerms as $oTerm) {
							wilcity_hero_render_list_of_terms_suggestion($oTerm);
						}
					}
				}
				?>
                <div class="highlight-lava-js__line"><span></span></div>
            </div>
        </div>
	<?php
	endif;
}

function wilcity_sc_render_hero($aAtts, $content)
{
    global $wilcityEnqueueHero;

    if (!$wilcityEnqueueHero) {
        wp_enqueue_script('HeroSearchForm');
        $wilcityEnqueueHero = true;
    }

	$aAtts = SCHelpers::mergeIsAppRenderingAttr($aAtts);

	if (wp_is_mobile()) {
		$aAtts['mobile_img_size'] = isset($aAtts['mobile_img_size']) ? $aAtts['mobile_img_size'] : 'medium';
	} else {
		$aAtts['img_size'] = isset($aAtts['img_size']) ? $aAtts['img_size'] : 'large';
	}

	if (SCHelpers::isApp($aAtts)) {
		echo '%SC%' . json_encode(SCHelpers::removeUnnecessaryParamOnApp($aAtts)) . '%SC%';

		return '';
	}

	$wrapper_class = $aAtts['extra_class'] . ' hero_module__Gwqkh';
	$searchBelowCategories = false;

	switch ($aAtts['search_form_position']) {
		case 'right':
			$wrapper_class .= ' hero_horizontal__1t27X';
			break;
		case 'bottom':
			$wrapper_class .= ' hero_fullWidth__1u0K7';
			$searchBelowCategories = true;
			break;
	}

	$searchBelowCategories = apply_filters('wilcity/hero/search-form-below-category', $searchBelowCategories);
	$wrapper_class .= ' ' . $aAtts['search_form_background'];
	?>
	<?php if ($aAtts['toggle_dark_and_white_background'] != 'enable') : ?>
    <div class="<?php echo esc_attr(trim($wrapper_class)); ?>">
<?php else: ?>
    <div class="<?php echo esc_attr(trim($wrapper_class)); ?>" data-image="grayscale">
<?php endif; ?>
	<?php if ($aAtts['bg_type'] == 'image') : ?>
    <div class="hero_background__xrTbV">
		<?php if (!empty($aAtts['bg_overlay'])) : ?>
            <div class="wil-overlay" style="background-color: <?php echo esc_attr($aAtts['bg_overlay']); ?>"></div>
		<?php endif; ?>
		<?php SCHelpers::renderLazyLoad($aAtts['image_bg'],
			['isNotRenderImg' => true, 'divClass' => 'hero_img__3VbXk bg-cover'], true); ?>
    </div>
<?php else: ?>
	<?php
	if (isset($aAtts['slider_bg']) && !empty($aAtts['slider_bg'])) :
		$aAtts['slider_bg'] = explode(',', $aAtts['slider_bg']); ?>
        <div class="hero_background__xrTbV">
			<?php if (!empty($aAtts['bg_overlay'])) : ?>
                <div class="wil-overlay" style="background-color: <?php echo esc_attr($aAtts['bg_overlay']); ?>"></div>
			<?php endif; ?>
            <div class="swiper__module swiper-container"
                 data-options='{"slidesPerView":1,"effect":"fade","autoplay":{"delay":5000}}'>
                <div class="swiper-wrapper">
					<?php
					foreach ($aAtts['slider_bg'] as $order => $imgID) :
						if ($order !== 0) :
							SCHelpers::renderLazyLoad(wp_get_attachment_image_url($imgID, $aAtts['img_size']),
								['isNotRenderImg' => true, 'divClass' => 'hero_img__3VbXk bg-cover']);
						else:
							?>
                            <div class="hero_img__3VbXk bg-cover"
                                 style="background-image: url(<?php echo wp_get_attachment_image_url($imgID,
								     $aAtts['img_size']); ?>);"></div>
						<?php endif; ?>
					<?php endforeach; ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
<?php endif; ?>
    <div class="wil-tb">
        <div class="wil-tb__cell">
            <div class="hero_container__9jNUX wil-text-center">
                <div class="hero_textWrapper__aU40s">
					<?php if (!empty($aAtts['heading_color'])) : ?>
                        <h1 style="color: <?php echo esc_attr($aAtts['heading_color']); ?>; font-size: <?php echo esc_attr($aAtts['heading_font_size']); ?>"
                            class="hero_title__47he3"><?php Wiloke::ksesHTML($aAtts['heading'], false); ?></h1>
					<?php else : ?>
                        <h1 style="font-size: <?php echo esc_attr($aAtts['heading_font_size']); ?>"
                            class="hero_title__47he3 color-primary"><?php Wiloke::ksesHTML($aAtts['heading'],
								false); ?></h1>
					<?php endif; ?>

					<?php if (!empty($aAtts['description_color'])) : ?>
                        <div class="hero_text__3ENGw"
                             style="color: <?php echo esc_attr($aAtts['description_color']); ?>; font-size: <?php echo esc_attr($aAtts['description_font_size']); ?>"><?php Wiloke::ksesHTML($aAtts['description'],
								false); ?></div>
					<?php else: ?>
                        <div class="hero_text__3ENGw"
                             style="font-size: <?php echo esc_attr($aAtts['description_font_size']); ?>"><?php Wiloke::ksesHTML($aAtts['description'],
								false); ?></div>
					<?php endif; ?>
					<?php do_action('wilcity/hero/before-search-form', $aAtts); ?>
					<?php if ($aAtts['toggle_button'] == 'enable') : ?>
                        <div class="mb-30 pb-20">
							<?php if (!empty($aAtts['button_background_color'])) : ?>
                            <a style="background-color: <?php echo esc_attr($aAtts['button_background_color']); ?>; color: <?php echo esc_attr($aAtts['button_text_color']); ?>"
                               class="wil-btn <?php echo esc_attr($aAtts['button_size']); ?> wil-btn--round"
                               href="<?php echo esc_url($aAtts['button_link']); ?>">
								<?php else: ?>
                                <a style="color: <?php echo esc_attr($aAtts['button_text_color']); ?>;"
                                   class="wil-btn wil-btn--primary2 <?php echo esc_attr($aAtts['button_size']); ?> wil-btn--round"
                                   href="<?php echo esc_url($aAtts['button_link']); ?>">
									<?php endif; ?>
									<?php if (!empty($aAtts['button_icon'])) : ?>
                                        <i class="<?php echo esc_attr($aAtts['button_icon'] .
											' color-primary'); ?>"></i>
									<?php endif; ?> <?php echo esc_html($aAtts['button_name']); ?>
                                </a>
                        </div>
					<?php endif; ?>
					<?php do_action('wilcity/hero/after-search-form', $aAtts); ?>
                </div>
				<?php
				if ($searchBelowCategories) {
					wilcity_sc_hero_search_form($aAtts);
					if (!empty($content)) {
						echo do_shortcode($content);
					}
				} else {
					if (!empty($content)) {
						echo do_shortcode($content);
					}
					wilcity_sc_hero_search_form($aAtts);
				}
				?>
            </div>
        </div>
    </div>
    </div>
	<?php
}
