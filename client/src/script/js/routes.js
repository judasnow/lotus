define([
    "underscore",
    "backbone",

    "utilities/auth",

    "v/page/seller_signup",
    "v/page/seller_login"

] , function(
    _,
    Backbone,

    auth,

    SellerSignupView,
    SellerLoginView
) {
    "use strict";

    var Routes = Backbone.Router.extend({

        routes: {
            "main": "_showMainPage",
            "seller_signup": "_showSellerSignupPage",
            "seller_login": "_showSellerLoginPage",
            "seller_logout": "_sellerLogout"
        },

        initialize: function() {
            _.bindAll(
                this ,

                "_showMainPage",
                "_showSellerSignupPage",
                "_showSellerLoginPage",
                "_sellerLogout"
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
        },

        _sellerLogout: function() {
            auth.doLogout( function() {
                window.routes.navigate( "main" , {trigger: true} );
            });
        }
    });

    return Routes;
});

