<?php
namespace WilcityWidgets\App;

use WILCITY_SC\SCHelpers;use WilokeListingTools\Framework\Helpers\General;use WilokeListingTools\Framework\Helpers\GetSettings;

class ListingPromotionOnListingSidebar extends \WP_Widget {
	public $aDef = ['title'=>'', 'post_type' => 'listing', 'orderby'=>'menu_order', 'order'=>'DESC', 'number_of_posts'=>4, 'taxonomy'=>'', 'style'=>'list', 'img_size'=>'medium'];

	public function __construct() {
		add_filter('wilcity/article-class', [$this, 'articleCssClass'], 10, 2);
		parent::__construct( 'wilcity_promote_listings_on_listing', WILCITY_WIDGET . ' (Promotion) Single Listing');
	}

	public function articleCssClass($style, $aAtts){
		if ( !isset($aAtts['isSlider']) ){
			return $style;
		}

		return 'listing_module__2EnGq wil-shadow js-listing-module';
	}

	public function form( $aInstance ) {
		$aInstance = wp_parse_args($aInstance, $this->aDef);

		$aPostTypes = General::getPostTypeOptions(false, false);
		?>
		<div class="widget-group">
			<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" id="<?php echo $this->get_field_id('title'); ?>" value="<?php echo esc_attr($aInstance['title']); ?>">
		</div>
		<div class="widget-group">
			<label for="<?php echo $this->get_field_id('post_type'); ?>">Post Type</label>
			<select class="widefat" name="<?php echo $this->get_field_name('post_type'); ?>" id="<?php echo $this->get_field_id('post_type'); ?>">
				<option value="all" <?php selected('all', $aInstance['post_type']); ?>>All Listing Types</option>
				<?php foreach ($aPostTypes as $option => $name): ?>
					<option value="<?php echo esc_attr($option); ?>" <?php selected($option, $aInstance['post_type']); ?>><?php echo esc_html($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="widget-group">
			<label for="<?php echo $this->get_field_id('number_of_posts'); ?>">Number of posts</label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('number_of_posts'); ?>" id="<?php echo $this->get_field_id('number_of_posts'); ?>" value="<?php echo esc_attr($aInstance['number_of_posts']); ?>">
		</div>
		<div class="widget-group">
			<label for="<?php echo $this->get_field_id('style'); ?>">Style</label>
			<select class="widefat" name="<?php echo $this->get_field_name('style'); ?>" id="<?php echo $this->get_field_id('style'); ?>">
				<?php foreach (['list'=>'Standard Listing Layout', 'list-full-width'=>'Full width List Layout', 'slider' => 'Slider Layout'] as $order => $name): ?>
					<option value="<?php echo esc_attr($order); ?>" <?php selected($order, $aInstance['style']); ?>><?php echo esc_html($name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
        <div class="widget-group">
			<label for="<?php echo $this->get_field_id('img_size'); ?>">Image Size</label>
			<input type="text" class="widefat" name="<?php echo $this->get_field_name('img_size'); ?>" id="<?php echo $this->get_field_id('img_size'); ?>" value="<?php echo esc_attr($aInstance['img_size']); ?>">
			<p>EG: Enter in image size key like large, medium, thumbnail</p>
		</div>
		<?php
	}

	public function widget( $aAtts, $aInstance ) {
        if ( $aInstance['post_type'] == 'all' ){
			$aPostTypes = General::getPostTypeKeys(false, false);
		}else{
			$aPostTypes = $aInstance['post_type'];
		}
		$aMetaKey = GetSettings::getPromotionKeyByPosition('listing_sidebar', true);
        if ( empty($aMetaKey) ){
		    return '';
		}

		$aArgs = [
			'post_type'         => $aPostTypes,
			'posts_per_page'    => $aInstance['number_of_posts'],
			'post_status'       => 'publish',
			'meta_key'          => $aMetaKey[0],
			'orderby'           => 'rand',
			'order'             => 'DESC',
			'isIgnoreAllQueries'=> true
		];
		$aInstance['img_size'] = SCHelpers::parseImgSize($aInstance['img_size']);

		$query = new \WP_Query($aArgs);
		if ( !$query->have_posts() ){
			wp_reset_postdata();
			return '';
		}

		echo $aAtts['before_widget'];
            if ( !empty($aInstance['title']) ){
                echo $aAtts['before_title']; ?><i class="la la-th-list"></i><span><?php echo esc_html($aInstance['title']); ?><?php echo $aAtts['after_title'];
            }
            if ( !function_exists('wilcityWidgetListStyle') ):
                \WilokeMessage::message([
                    'status' => 'danger',
                    'msg' => 'Please click on Appearance -> Install Plugins -> Active Wilcity Shortcode plugin',
                    'icon' => 'la la-frown-o'
                ]);
            else:
                switch ($aInstance['style']):
                    case 'list':
                ?>
                    <div class="widget-post">
                        <?php
                        while ( $query->have_posts() ){
                            $query->the_post();
                            wilcityWidgetListStyle($query->post, ['isShowComment'=>true]);
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php
                        break;
                    case 'list-full-width':
                    ?>
                    <div class="widget-listing">
                        <?php
                        while ( $query->have_posts() ){
                            $query->the_post();
                            wilcityRenderFullWidthListItem($query->post);
                        } wp_reset_postdata();
                        ?>
                    </div>
                    <?php
                        break;
                    default: ?>
                        <div class="content-box_body__3tSRB">
                            <div class="swiper__module swiper-container swiper--button-abs" data-options='{"slidesPerView":"auto","spaceBetween":10,"speed":1000,"autoplay":true,"loop":true}'>
                                <div class="swiper-wrapper">
                                    <?php
                                    while ( $query->have_posts() ){
                                        $query->the_post();
                                         wilcity_render_grid_item($query->post, ['img_size'=>$aInstance['img_size'], 'isSlider'=>true, 'style'=>'grid','TYPE'=>'LISTING_SIDEBAR']);
                                    } wp_reset_postdata();
                                    ?>
                                </div>
                                <div class="swiper-button-custom">
                                    <div class="swiper-button-prev-custom"><i class='la la-angle-left'></i></div>
                                    <div class="swiper-button-next-custom"><i class='la la-angle-right'></i></div>
                                </div>
                            </div>
                        </div>
                    <?php break; ?>
                <?php endswitch; ?>
            <?php endif;
        echo $aAtts['after_widget'];
    }

	public function update( $aNewInstance, $aOldInstance ) {
		$aInstance = $aOldInstance;
		foreach ($aNewInstance as $key => $val){
			$aInstance[$key] = strip_tags($val);
		}
		return $aInstance;
	}
}
