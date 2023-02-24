jQuery(document).ready(function($){
	$(document.body).on('popup_wilcity-quick-search-form-popup_open', function(){
		$('body').addClass('popup-active');
	});

	$(document.body).on('popup_wilcity-quick-search-form-popup_close', function(){
		$('body').removeClass('popup-active');
	});
});

jQuery(document).ready(function(){
	if ( typeof sbi_init === 'function' ){
		jQuery('body').on('wilcity/single/loaded/wilcity_single_navigation_flux_instagram', function(){
			setTimeout(function(){
				sbi_init(function(imagesArr,transientName) {
					sbi_cache_all(imagesArr,transientName);
				});
			}, 400);

		});
	}
})