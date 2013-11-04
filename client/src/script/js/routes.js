define([
    "underscore",
    "backbone",
    "utility",

    "v/seller_signup"

] , function(
    _,
    Backbone,
    utility,

    SellerSignupView
) {
    "use strict";

    var Routes = Backbone.Router.extend({

        routes: {
            "seller_signup": "_showSellerSignup"
        },

        initialize: function() {
            _.bindAll( this , "_showSellerSignup" );
        },

        _showSellerSignup: function() {
            new SellerSignupView();
        }
    });

    return Routes;
});

