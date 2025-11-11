(function ($) {
    'use strict';

    var tournament = {};
    edgtf.modules.tournament = tournament;

    tournament.edgtfOnDocumentReady = edgtfOnDocumentReady;
    tournament.edgtfOnWindowResize = edgtfOnWindowResize;

    $(document).ready(edgtfOnDocumentReady);
    $(window).resize(edgtfOnWindowResize);

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function edgtfOnDocumentReady() {
        edgtfInitTournamentSingle().init();
    }

    /*
     All functions to be called on $(window).resize() should be in this function
     */
    function edgtfOnWindowResize() {
        edgtfInitTournamentSingle().init();
    }

    /**
     * * Tournament Single Functionality
     */
    var edgtfInitTournamentSingle = function () {

        var topInfoHeightCalculation = function( holder ) {

            var topInfo = holder,
                topInfoInner = topInfo.find('.edgtf-tournament-single-info-top-inner'),
                mainInfo = $('.edgtf-tournament-info-main');

            if ( topInfo.length ) {
                topInfoInner.height( topInfo.outerHeight() - ( mainInfo.outerHeight() / 2 ) );
            }
        };

        return {
			init: function () {

			    var infoTop = $('.edgtf-tournament-single-info-top');

				if ( infoTop.length ) {
					topInfoHeightCalculation( infoTop );
				}
			}
		};
    };

})(jQuery);