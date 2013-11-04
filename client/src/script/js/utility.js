define([

    "zepto",
    "async"

], function( $ , async ) {
    "use strict";

    var utility = {};

    var $wrapper = $( "#wrapper .box" );

    var fadeOutPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 0
        }, {
            duration: 200,
            complete: cb
        });
    };//}}}

    var fadeInPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 100
        }, {
            duration: 50,
            complete: cb
        });
    };//}}}

    utility.loadPage = function( html ) {
    //{{{
        async.series([
            function( cb ) {
                fadeOutPage( cb );
            },
            function( cb ) {
                $wrapper.html( html )
            },
            function( cb ) {
                fadeInPage( cb );
            }
        ]);
    };//}}}

    return utility;
});
