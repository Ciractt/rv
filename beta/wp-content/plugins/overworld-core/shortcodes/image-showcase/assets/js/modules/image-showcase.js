(function($) {
	'use strict';
	
	var imageShowcase = {};
	edgtf.modules.imageShowcase = imageShowcase;
	
	imageShowcase.edgtfInitImageShowcase = edgtfInitImageShowcase;

	imageShowcase.edgtfOnWindowLoad = edgtfOnWindowLoad;

	$(window).on( 'load', edgtfOnWindowLoad);

	/*
	 All functions to be called on $(window).load() should be in this function
	 */
	function edgtfOnWindowLoad() {
		setTimeout(function () {
			edgtfInitImageShowcase();
		}, 100);
	}

	/*
	 **	Init Image Showcase shortcode
	 */
	function edgtfInitImageShowcase(){
		if (edgtf.windowWidth > 1024) {
			var imageShowcase = $('.edgtf-image-showcase');

			if(imageShowcase.length) {
				imageShowcase.each(function() {

					var thisImageShowcase = $(this),
						thisImageShowcaseSlider = thisImageShowcase.find('.edgtf-is-slider .owl-stage'),
						thisImageShowcaseSliderHeight,
						thisImageShowcaseInfo = thisImageShowcase.find('.edgtf-is-info');

					if (thisImageShowcase.hasClass('edgtf-is-full-height')) {

						var itemImageHolder = thisImageShowcase.find('.edgtf-is-image'),
		                    topOffset = thisImageShowcase.offset().top,
		                    footer = $('.edgtf-page-footer'),
			                footerHeight = footer.length ? footer.outerHeight() : 0,
			                contentBottom = $('.edgtf-content-bottom'),
			                contentBottomHeight = contentBottom.length ? contentBottom.outerHeight() : 0,
		                    height = edgtf.windowHeight - topOffset - footerHeight - contentBottomHeight;

						itemImageHolder.css('height', height);

					}

					thisImageShowcaseSliderHeight = thisImageShowcaseSlider.length ? thisImageShowcaseSlider.height() : 0;

					if (thisImageShowcaseInfo.length && thisImageShowcaseSliderHeight > 0) {
						thisImageShowcase.height(thisImageShowcaseSliderHeight);
					}
				});
			}
		}
	}
	
})(jQuery);