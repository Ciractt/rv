(function($) {
	'use strict';
	
	var button = {};
	edgtf.modules.button = button;
	
	button.edgtfButton = edgtfButton;
	
	
	button.edgtfOnDocumentReady = edgtfOnDocumentReady;
	
	$(document).ready(edgtfOnDocumentReady);
	
	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function edgtfOnDocumentReady() {
		edgtfButton().init();
	}
	
	/**
	 * Button object that initializes whole button functionality
	 * @type {Function}
	 */
	var edgtfButton = function() {
		//all buttons on the page
		var buttons = $('.edgtf-btn');
		
		/**
		 * Initializes button hover color
		 * @param button current button
		 */
		var buttonHoverColor = function(button) {
			if(typeof button.data('hover-color') !== 'undefined') {
				var changeButtonColor = function(event) {
					event.data.button.css('color', event.data.color);
				};
				
				var originalColor = button.css('color');
				var hoverColor = button.data('hover-color');
				
				button.on('mouseenter', { button: button, color: hoverColor }, changeButtonColor);
				button.on('mouseleave', { button: button, color: originalColor }, changeButtonColor);
			}
		};
		
		/**
		 * Initializes button hover background color
		 * @param button current button
		 */
		var buttonHoverBgColor = function(button) {
			if(typeof button.data('hover-bg-color') !== 'undefined' && !button.hasClass('edgtf-btn-stripe')) {
				var changeButtonBg = function(event) {
					event.data.button.css('background-color', event.data.color);
				};
				
				var originalBgColor = button.css('background-color');
				var hoverBgColor = button.data('hover-bg-color');
				
				button.on('mouseenter', { button: button, color: hoverBgColor }, changeButtonBg);
				button.on('mouseleave', { button: button, color: originalBgColor }, changeButtonBg);
			}
		};
		
		/**
		 * Initializes button border color
		 * @param button
		 */
		var buttonHoverBorderColor = function(button) {
			if(typeof button.data('hover-border-color') !== 'undefined') {
				var changeBorderColor = function(event) {
					event.data.button.css('border-color', event.data.color);
				};
				
				var originalBorderColor = button.css('borderTopColor'); //take one of the four sides
				var hoverBorderColor = button.data('hover-border-color');
				
				button.on('mouseenter', { button: button, color: hoverBorderColor }, changeBorderColor);
				button.on('mouseleave', { button: button, color: originalBorderColor }, changeBorderColor);
			}
		};

        /**
         * Initializes button hover svg fill color
         * @param button current button
         */

        var buttonHoverFillSVGColor = function(button) {
            if(typeof button.data('hover-fill-color') !== 'undefined') {
                var changeButtonSVGFill = function(event) {
                    event.data.button.attr('fill', event.data.color);
                };

                var originalBgColor = button.find('svg > path').attr('fill');
                var hoverBgColor = button.data('hover-fill-color');

                button.on('mouseenter', { button: button.find('svg > path'), color: hoverBgColor }, changeButtonSVGFill);
                button.on('mouseleave', { button: button.find('svg > path'), color: originalBgColor }, changeButtonSVGFill);
            }
        };


        /**
         * Initializes button hover svg stroke color
         * @param button current button
         */

        var buttonHoverStrokeSVGColor = function(button) {
            if(typeof button.data('hover-stroke-color') !== 'undefined') {
                var changeButtonSVGStroke = function(event) {
                    event.data.button.attr('stroke', event.data.color);
                };

                var originalBgColor = button.find('svg > path').attr('stroke');
                var hoverBgColor = button.data('hover-stroke-color');

                button.on('mouseenter', { button: button.find('svg > path'), color: hoverBgColor }, changeButtonSVGStroke);
                button.on('mouseleave', { button: button.find('svg > path'), color: originalBgColor }, changeButtonSVGStroke);
            }
		};
		
		var buttonStripe = function() {
			var buttons = $('.edgtf-btn-solid, .edgtf-btn-outline');
			buttons.addClass('edgtf-btn-stripe');
		};

		var buttonStripeAnimation = function(button) {
			if (button.hasClass('edgtf-btn-stripe')) {
				button.append('<div class="edgtf-btn-bg-holder"></div>');
				if(typeof button.data('hover-bg-color') !== 'undefined') {
					var hoverBgColor = button.data('hover-bg-color');
					button.find('.edgtf-btn-bg-holder').css('background-color', hoverBgColor);
				}
			}
		};

		var buttonsShopStripe = function() {
			var buttons = $('.add_to_cart_button, .single_add_to_cart_button, .button.wc-forward');
			buttons.addClass('edgtf-btn-stripe');
			buttons.append('<div class="edgtf-btn-bg-holder"></div>');
		};

		return {
			init: function() {
				buttonStripe();
				buttonsShopStripe();

				if(buttons.length) {
					buttons.each(function() {
						buttonStripeAnimation($(this));
						buttonHoverColor($(this));
						buttonHoverBgColor($(this));
						buttonHoverBorderColor($(this));
                        buttonHoverFillSVGColor($(this));
                        buttonHoverStrokeSVGColor($(this));
					});
				}
			}
		};
	};
	
})(jQuery);