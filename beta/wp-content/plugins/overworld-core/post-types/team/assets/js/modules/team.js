(function ($) {
    'use strict';

    var team = {};
    edgtf.modules.team = team;

    team.edgtfOnDocumentReady = edgtfOnDocumentReady;
    team.edgtfOnWindowResize = edgtfOnWindowResize;

    $(document).ready(edgtfOnDocumentReady);
    $(window).resize(edgtfOnWindowResize);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function edgtfOnDocumentReady() {
        edgtfInitTeamSingle().init();
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function edgtfOnWindowResize() {
        edgtfInitTeamSingle().init();
    }

    /**
     * Team Single Functionality
     */
    var edgtfInitTeamSingle = function () {

        var topInfoHeightCalculation = function( holder ) {

            var topInfo = holder,
                topInfoInner = topInfo.find('.edgtf-team-single-info-top-inner'),
                mainInfo = $('.edgtf-team-info-main');

            if ( topInfo.length ) {
                topInfoInner.height( topInfo.outerHeight() - ( mainInfo.outerHeight() / 2 ) );
            }
        };

        return {
			init: function () {

			    var infoTop = $('.edgtf-team-single-info-top');

				if ( infoTop.length ) {
					topInfoHeightCalculation( infoTop );
				}
			}
		};
    };

})(jQuery);