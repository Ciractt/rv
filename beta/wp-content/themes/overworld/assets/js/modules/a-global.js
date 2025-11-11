(function ($) {
    "use strict";

    window.edgtf = {};
    edgtf.modules = {};

    edgtf.scroll = 0;
    edgtf.window = $(window);
    edgtf.document = $(document);
    edgtf.windowWidth = $(window).width();
    edgtf.windowHeight = $(window).height();
    edgtf.body = $('body');
    edgtf.html = $('html, body');
    edgtf.htmlEl = $('html');
    edgtf.menuDropdownHeightSet = false;
    edgtf.defaultHeaderStyle = '';
    edgtf.minVideoWidth = 1500;
    edgtf.videoWidthOriginal = 1280;
    edgtf.videoHeightOriginal = 720;
    edgtf.videoRatio = 1.61;

    edgtf.edgtfOnDocumentReady = edgtfOnDocumentReady;
    edgtf.edgtfOnWindowLoad = edgtfOnWindowLoad;
    edgtf.edgtfOnWindowResize = edgtfOnWindowResize;
    edgtf.edgtfOnWindowScroll = edgtfOnWindowScroll;

    $(document).ready(edgtfOnDocumentReady);
    $(window).on( 'load', edgtfOnWindowLoad);
    $(window).resize(edgtfOnWindowResize);
    $(window).scroll(edgtfOnWindowScroll);

    /* 
     All functions to be called on $(document).ready() should be in this function
     */
    function edgtfOnDocumentReady() {
        edgtf.scroll = $(window).scrollTop();
        edgtfBrowserDetection();

        //set global variable for header style which we will use in various functions
        if (edgtf.body.hasClass('edgtf-dark-header')) {
            edgtf.defaultHeaderStyle = 'edgtf-dark-header';
        }
        if (edgtf.body.hasClass('edgtf-light-header')) {
            edgtf.defaultHeaderStyle = 'edgtf-light-header';
        }
    }

    /* 
     All functions to be called on $(window).load() should be in this function
     */
    function edgtfOnWindowLoad() {

    }

    /* 
     All functions to be called on $(window).resize() should be in this function
     */
    function edgtfOnWindowResize() {
        edgtf.windowWidth = $(window).width();
        edgtf.windowHeight = $(window).height();
    }

    /* 
     All functions to be called on $(window).scroll() should be in this function
     */
    function edgtfOnWindowScroll() {
        edgtf.scroll = $(window).scrollTop();
    }

    //set boxed layout width variable for various calculations

    switch (true) {
        case edgtf.body.hasClass('edgtf-grid-1300'):
            edgtf.boxedLayoutWidth = 1350;
            //edgtf.gridWidth = 1300;
            break;
        case edgtf.body.hasClass('edgtf-grid-1200'):
            edgtf.boxedLayoutWidth = 1250;
            //edgtf.gridWidth = 1200;
            break;
        case edgtf.body.hasClass('edgtf-grid-1000'):
            edgtf.boxedLayoutWidth = 1050;
            //edgtf.gridWidth = 1000;
            break;
        case edgtf.body.hasClass('edgtf-grid-800'):
            edgtf.boxedLayoutWidth = 850;
            //edgtf.gridWidth = 800;
            break;
        default:
            edgtf.boxedLayoutWidth = 1150;
            //edgtf.gridWidth = 1100;
            break;
    }

    edgtf.gridWidth = function () {
        var gridWidth = 1100;

        switch (true) {
            case edgtf.body.hasClass('edgtf-grid-1300') && edgtf.windowWidth > 1400:
                gridWidth = 1300;
                break;
            case edgtf.body.hasClass('edgtf-grid-1200') && edgtf.windowWidth > 1300:
                gridWidth = 1200;
                break;
            case edgtf.body.hasClass('edgtf-grid-1000') && edgtf.windowWidth > 1200:
                gridWidth = 1200;
                break;
            case edgtf.body.hasClass('edgtf-grid-800') && edgtf.windowWidth > 1024:
                gridWidth = 800;
                break;
            default:
                break;
        }

        return gridWidth;
    };

    edgtf.transitionEnd = (function () {
        var el = document.createElement('transitionDetector'),
            transEndEventNames = {
                'WebkitTransition': 'webkitTransitionEnd', // Saf 6, Android Browser
                'MozTransition': 'transitionend', // only for FF < 15
                'transition': 'transitionend' // IE10, Opera, Chrome, FF 15+, Saf 7+
            };

        for (var t in transEndEventNames) {
            if (el.style[t] !== undefined) {
                return transEndEventNames[t];
            }
        }
    })();

    edgtf.animationEnd = (function () {
        var el = document.createElement("animationDetector");

        var animations = {
            "animation": "animationend",
            "OAnimation": "oAnimationEnd",
            "MozAnimation": "animationend",
            "WebkitAnimation": "webkitAnimationEnd"
        };

        for (var t in animations) {
            if (el.style[t] !== undefined) {
                return animations[t];
            }
        }
    })();

    /*
     * Browser detection
     */
    function edgtfBrowserDetection() {
        var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor),
            isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),
            isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1,
            isIE = window.navigator.userAgent.indexOf("MSIE ");

        if (isChrome) {
            edgtf.body.addClass('edgtf-chrome');
        }
        if (isSafari) {
            edgtf.body.addClass('edgtf-safari');
        }
        if (isFirefox) {
            edgtf.body.addClass('edgtf-firefox');
        }
        if (isIE > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            edgtf.body.addClass('edgtf-ms-explorer');
        }
        if (/Edge\/\d./i.test(navigator.userAgent)) {
            edgtf.body.addClass('edgtf-edge');
        }
    }

})(jQuery);