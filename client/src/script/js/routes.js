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
            "seller_signup": "_showSellerSignup",
            "seller_login": "_showSellerLogin"
        },

        initialize: function() {
            _.bindAll(
                this ,

                "_showSellerSignup",
                "_showSellerLogin"
            );
        },

        _showSellerSignup: function() {
            new SellerSignupView();
        },

        _showSellerLogin: function() {
            new SellerLoginView();
        }
    });

    return Routes;
});

