define([
    "underscore",
    "backbone",

    "v/page/seller_signup",
    "v/page/seller_login"

] , function(
    _,
    Backbone,

    SellerSignupView,
    SellerLoginView
) {
    "use strict";

    var Routes = Backbone.Router.extend({

        routes: {
            "main": "_showMainPage",
            "seller_signup": "_showSellerSignupPage",
            "seller_login": "_showSellerLoginPage"
        },

        initialize: function() {
            _.bindAll(
                this ,

                "_showMainPage",
                "_showSellerSignupPage",
                "_showSellerLoginPage"
            );
        },

        _showMainPage: function() {
            console.log( "main page" );
        },

        _showSellerSignupPage: function() {
            new SellerSignupView();
        },

        _showSellerLoginPage: function() {
            new SellerLoginView();
        }
    });

    return Routes;
});

