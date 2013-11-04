define([

    "zepto",
    "async"

], function( $ , async ) {
    "use strict";

    var page = {};

    var $wrapper = $( "#wrapper" );

    var fadeOutPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 0
        }, {
            duration: 30,
            complete: cb
        });
    };//}}}

    var fadeInPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 100
        }, {
            duration: 30,
            complete: cb
        });
    };//}}}

    // 需要注意的地方就是 page 变换 替换的是 box 部分的内容
    page.loadPage = function( $el ) {
    //{{{
        async.series([
            function( cb ) {
                fadeOutPage( cb );
            },
            function( cb ) {
                $wrapper.html( $el );
                cb();
            },
            function( cb ) {
                fadeInPage( cb );
            }
        ], function( err , res ) {
            //@TODO
        });
    };//}}}

    return page;
});
