define([
    'underscore',
    'backbone',

    'utilities/auth',
    'utilities/common',

    'v/page/seller_signup',
    'v/page/seller_login',
    'v/hot_shop_list'

] , function(
    _,
    Backbone,

    auth,
    common,

    SellerSignupView,
    SellerLoginView,
    HotShopListView
) {
    'use strict';

    var Routes = Backbone.Router.extend({

        routes: {
            '': '_showMainPage',
            'main': '_showMainPage',
            'seller_signup': '_showSellerSignupPage',
            'seller_login': '_showSellerLoginPage',
            'seller_logout': '_sellerLogout'
        },

        initialize: function() {
            _.bindAll(
                this ,

                '_showMainPage',
                '_showSellerSignupPage',
                '_showSellerLoginPage',
                '_sellerLogout'
            );
        },

        _showMainPage: function() {
            common.log( 'now in main page' );

            var hotShopListView = new HotShopListView();
        },

        _showSellerSignupPage: function() {
            new SellerSignupView();
        },

        _showSellerLoginPage: function() {
            new SellerLoginView();
        },

        _sellerLogout: function() {
            auth.doLogout( function() {
                window.routes.navigate( 'main' , {trigger: true} );
            });
        }
    });

    return Routes;
});

