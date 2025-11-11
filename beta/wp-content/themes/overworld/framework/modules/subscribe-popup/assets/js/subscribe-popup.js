(function ($) {
	"use strict";
	
	var subscribePopup = {};
	edgtf.modules.subscribePopup = subscribePopup;
	
	subscribePopup.edgtfOnWindowLoad = edgtfOnWindowLoad;
	
	$(window).on( 'load', edgtfOnWindowLoad);
	
	/*
	 All functions to be called on $(window).load() should be in this function
	 */
	function edgtfOnWindowLoad() {
		edgtfSubscribePopup();
	}
	
	function edgtfSubscribePopup() {
		var popupOpener = $('.edgtf-subscribe-popup-holder'),
			popupClose = $('.edgtf-sp-close');
		
		if (popupOpener.length) {
			var popupPreventHolder = popupOpener.find('.edgtf-sp-prevent'),
				disabledPopup = 'no';
			
			if (popupPreventHolder.length) {
				var isLocalStorage = popupOpener.hasClass('edgtf-sp-prevent-cookies'),
					popupPreventInput = popupPreventHolder.find('.edgtf-sp-prevent-input'),
					preventValue = popupPreventInput.data('value');
				
				if (isLocalStorage) {
					disabledPopup = localStorage.getItem('disabledPopup');
					sessionStorage.removeItem('disabledPopup');
				} else {
					disabledPopup = sessionStorage.getItem('disabledPopup');
					localStorage.removeItem('disabledPopup');
				}
				
				popupPreventHolder.children().on('click', function (e) {
					if ( preventValue !== 'yes' ) {
						preventValue = 'yes';
						popupPreventInput.addClass('edgtf-sp-prevent-clicked').data('value', 'yes');
					} else {
						preventValue = 'no';
						popupPreventInput.removeClass('edgtf-sp-prevent-clicked').data('value', 'no');
					}
					
					if (preventValue === 'yes') {
						if (isLocalStorage) {
							localStorage.setItem('disabledPopup', 'yes');
						} else {
							sessionStorage.setItem('disabledPopup', 'yes');
						}
					} else {
						if (isLocalStorage) {
							localStorage.setItem('disabledPopup', 'no');
						} else {
							sessionStorage.setItem('disabledPopup', 'no');
						}
					}
				});
			}
			
			if (disabledPopup !== 'yes') {
				if (edgtf.body.hasClass('edgtf-sp-opened')) {
					edgtf.body.removeClass('edgtf-sp-opened');
					edgtf.modules.common.edgtfEnableScroll();
				} else {
					edgtf.body.addClass('edgtf-sp-opened');
					edgtf.modules.common.edgtfDisableScroll();
				}
				
				popupClose.on('click', function (e) {
					e.preventDefault();
					
					edgtf.body.removeClass('edgtf-sp-opened');
					edgtf.modules.common.edgtfEnableScroll();
				});
				
				//Close on escape
				$(document).keyup(function (e) {
					if (e.keyCode === 27) { //KeyCode for ESC button is 27
						edgtf.body.removeClass('edgtf-sp-opened');
						edgtf.modules.common.edgtfEnableScroll();
					}
				});
			}
		}
	}
	
})(jQuery);