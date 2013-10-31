define([
    "underscore",
    "backbone",
    "utility"

] , function( _ , Backbone , utility ) {
    "use strict";

    var Routes = Backbone.Router.extend({

        routes: {
            "seller_signup": "_showSellerSignup",
            "seller_login": "_showSellerLogin"
        },

        initialize: function() {
            _.bindAll( this , "_showSellerSignup" , "_showSellerLogin" );
        },

        _showSellerLogin: function() {
            //utility.loadPage( "123" );
        },

        _showSellerSignup: function() {

        }
    });

    return Routes;
});

