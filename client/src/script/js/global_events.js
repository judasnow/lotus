define([

    "zepto",
    "underscore",
    "backbone",

    "utilities/common",

    "v/nav"

], function(
    $ ,
    _ ,
    Backbone,

    common
) {
    "use strict";

    var GlobleEvents = function( views ) {
        var navView = views.nav_view;
        var e = {};
        _.extend( e , Backbone.Events );

        e.on( "show_loading" , navView.showLoading );
        e.on( "hide_loading" , navView.hideLoading );

        e.on( "login_ok" , function() {
            common.getObjectUserInfo( function() {
                navView.showObjectUserInfo();
            });
        });

        this.e = e;
    };

    return GlobleEvents;
});
