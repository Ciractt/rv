(function ($) {
	'use strict';
	
	var dualImageCarousel = {};
	edgtf.modules.dualImageCarousel = dualImageCarousel;
	
	dualImageCarousel.edgtfDualImageCarousel = edgtfDualImageCarousel;
	
	dualImageCarousel.edgtfOnDocumentReady = edgtfOnDocumentReady;
	
	$(document).ready(edgtfOnDocumentReady);
	
	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function edgtfOnDocumentReady() {
		edgtfDualImageCarousel();
	}

	/*
	 ** Dual Image Carousel
	*/
    function edgtfDualImageCarousel() {
        var swipers = $('.swiper-container.edgtf-dual-image-carousel');

        if (swipers.length) {
            swipers.each(function () {
                var swiper = $(this),
                    nav = $(this).find('.swiper-navigation'),
	                navTopOffset = 38,
	                numberOfItems = 1,
	                navPrevNo = nav.find('.edgtf-swiper-button-prev .edgtf-text'),
                    navNextNo = nav.find('.edgtf-swiper-button-next .edgtf-text'),
                    swiperSlide = swiper.find('.swiper-slide'),
                    foregroundSlidePosition = swiper.data('foreground-slides-position');

                if (edgtf.windowWidth <= 1024) {
					navTopOffset = 18;
				}
	
	            if (edgtf.windowWidth <= 480) {
		            navTopOffset = 30;
	            }

                if (typeof swiper.data('number-of-items') !== 'undefined' && swiper.data('number-of-items') !== false) {
		            numberOfItems = swiper.data('number-of-items');
	            }

                swiperSlide.each(function () {
                    if (foregroundSlidePosition !== '') {
                        $(this).find('.edgtf-slide-foreground-image-holder').css('margin-top', foregroundSlidePosition);
                    }
                });

                // Function to update CSS before content
                var updateNavNumbers = function(prevNum, nextNum) {
                    navPrevNo.attr('data-prev-num', prevNum);
                    navNextNo.attr('data-next-num', nextNum);
                };

                // Function to determine next and previous item numbers
                var determineNavNumbers = function(index) {

                    var prevIndex = index - 1,
                        nextIndex = index + 1;

                    if (prevIndex <= 0) {
                        prevIndex = numberOfItems;
                    }
                    if (nextIndex > numberOfItems) {
                        nextIndex = 1;
                    }

                    updateNavNumbers(prevIndex, nextIndex);
                };

	            var swiperSlider = new Swiper(swiper, {
                    loop: true,
                    parallax: true,
                    speed: 1000,
                    mousewheelControl: false,
                    slidesPerView: 'auto',
                    centeredSlides: true,
                    spaceBetween: 215,
                    autoplay: true,
                    navigation: {
                        nextEl: '.edgtf-swiper-button-next',
                        prevEl: '.edgtf-swiper-button-prev',
                    },
                    on: {
                        init: function () {
                            swiper.addClass('edgtf-dual-image-carousel-loaded');
                        },
	                    transitionStart: function () {
                            var navTop = swiper.find('.swiper-slide-active .edgtf-slide-background-image').height() + navTopOffset;

                            nav.css('top', navTop + 'px');

                            determineNavNumbers(this.realIndex + 1);
                        }
                    },
		            breakpoints: {
				    0: {
				      spaceBetween: 20
				    },
				    480: {
				      spaceBetween: 40
				    },
		            680: {
				      spaceBetween: 60
				    },
		            768: {
				      spaceBetween: 80
				    },
		            1024: {
				      spaceBetween: 100
				    },
		            1200: {
				      spaceBetween: 120
				    },
		            1280: {
				      spaceBetween: 140
				    },
		            1366: {
				      spaceBetween: 160
				    },
		            1440: {
				      spaceBetween: 180
				    },
		            1920: {
				      spaceBetween: 215
				    }
				  }
                });

                $(this).waitForImages(function() {
                    var navTop = $(this).find('.edgtf-slide-background-image').height() + navTopOffset;

                    nav.css('top', navTop + 'px');
                });
            });
        }
    }
    
})(jQuery);