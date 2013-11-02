define([

    "zepto"

], function( $ ) {
    "use strict";

    var utility = {};

    var $wrapper = $( "#wrapper .box" );

    utility.loadPage = function( html ) {
        var _$wrapper = $wrapper.html( html );
    };

    return utility;
});
