define([
    "underscore",
    "backbone",
    "utility"

] , function( _ , Backbone , utility ) {
    "use strict";

    var Routes = Backbone.Router.extend({

        routes: {
            "seller_signup": "_showSellerSignup"
        },

        initialize: function() {
            _.bindAll( this , "_showSellerSignup" );
        },

        _showSellerSignup: function() {
            
        }
    });

    return Routes;
});

