(function ($) {
    'use strict';

    var player = {};
    edgtf.modules.player = player;

    player.edgtfOnDocumentReady = edgtfOnDocumentReady;
    player.edgtfOnWindowResize = edgtfOnWindowResize;

    $(document).ready(edgtfOnDocumentReady);
    $(window).resize(edgtfOnWindowResize);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function edgtfOnDocumentReady() {
        edgtfInitPlayerSingle().init();
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function edgtfOnWindowResize() {
        edgtfInitPlayerSingle().init();
    }

    /**
     * Player Single Functionality
     */
    var edgtfInitPlayerSingle = function () {

        var topInfoHeightCalculation = function( holder ) {

            var topInfo = holder,
                topInfoInner = topInfo.find('.edgtf-player-single-info-top-inner'),
                mainInfo = $('.edgtf-player-info-main');

            if ( topInfo.length ) {
                topInfoInner.height( topInfo.outerHeight() - ( mainInfo.outerHeight() / 2 ) );
            }
        };

        return {
			init: function () {

			    var infoTop = $('.edgtf-player-single-info-top');

				if ( infoTop.length ) {
					topInfoHeightCalculation( infoTop );
				}
			}
		};
    };

})(jQuery);