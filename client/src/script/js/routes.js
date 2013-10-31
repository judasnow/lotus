define([
    "underscore",
    "backbone"

] , function( _ , Backbone ) {
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
            
        },

        _showSellerSignup: function() {

        }
    });

    return Routes;
});

