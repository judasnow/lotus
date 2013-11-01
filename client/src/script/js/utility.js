define([

    "zepto"

], function( $ ) {
    "use strict";

    var utility = {};

    var _$wrapper = $( "#wrapper .box" );

    utility.loadPage = function( html ) {
        _$wrapper.html( html );
    };

    return utility;
});
