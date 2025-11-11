(function($) {
    "use strict";

    var headerDivided = {};
    edgtf.modules.headerDivided = headerDivided;
	
	headerDivided.edgtfOnDocumentReady = edgtfOnDocumentReady;
	headerDivided.edgtfOnWindowResize = edgtfOnWindowResize;

    $(document).ready(edgtfOnDocumentReady);
    $(window).resize(edgtfOnWindowResize);
    
    /* 
        All functions to be called on $(document).ready() should be in this function
    */
    function edgtfOnDocumentReady() {
	    edgtfInitDividedHeaderMenu();
    }

    /* 
        All functions to be called on $(window).resize() should be in this function
    */
    function edgtfOnWindowResize() {
        edgtfInitDividedHeaderMenu();
    }

    /**
     * Init Divided Header Menu
     */
    function edgtfInitDividedHeaderMenu(){
        if(edgtf.body.hasClass('edgtf-header-divided')){
	        //get left side menu width
	        var menuArea = $('.edgtf-menu-area, .edgtf-sticky-header'),
		        menuAreaWidth = menuArea.width(),
		        menuAreaSidePadding = parseInt(menuArea.find('.edgtf-vertical-align-containers').css('paddingLeft'), 10),
		        menuAreaItem = $('.edgtf-main-menu > ul > li > a'),
		        menuItemPadding = 0,
		        logoArea = menuArea.find('.edgtf-logo-wrapper .edgtf-normal-logo'),
		        logoAreaWidth = 0;
	
	        menuArea.waitForImages(function() {
	        	
		        if(menuArea.find('.edgtf-grid').length) {
			        menuAreaWidth = menuArea.find('.edgtf-grid').outerWidth();
		        }
		
		        if(menuAreaItem.length) {
			        menuItemPadding = parseInt(menuAreaItem.css('paddingLeft'), 10);
		        }
		
		        if(logoArea.length) {
			        logoAreaWidth = logoArea.width() / 2;
		        }
		
		        var menuAreaLeftRightSideWidth = Math.round(menuAreaWidth/2 - menuItemPadding - logoAreaWidth - menuAreaSidePadding);
		
		        menuArea.find('.edgtf-position-left').width(menuAreaLeftRightSideWidth);
		        menuArea.find('.edgtf-position-right').width(menuAreaLeftRightSideWidth);
		
		        menuArea.css('opacity',1);
		
		        if (typeof edgtf.modules.header.edgtfSetDropDownMenuPosition === "function") {
			        edgtf.modules.header.edgtfSetDropDownMenuPosition();
		        }
		        if (typeof edgtf.modules.header.edgtfSetDropDownWideMenuPosition === "function") {
			        edgtf.modules.header.edgtfSetDropDownWideMenuPosition();
		        }
	        });
        }
    }

})(jQuery);