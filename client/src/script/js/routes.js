define([
    'underscore',
    'backbone',

    'utilities/auth',
    'utilities/common',

    'v/page/seller_signup',
    'v/page/seller_login',
    'v/page/shop',

    'v/hot_shop_list',
    'v/hot_product_list',
    'v/page/product_detail'

] , function(
    _,
    Backbone,

    auth,
    common,

    //pages
    SellerSignupView,
    SellerLoginView,
    ShopPageView,

    HotShopListView,
    HotProductListView,
    ProductDetailPageView
) {
    'use strict';

    var Routes = Backbone.Router.extend({

        routes: {
            '': '_showMainPage',
            'main': '_showMainPage',
            'seller_signup': '_showSellerSignupPage',
            'seller_login': '_showSellerLoginPage',
            'seller_logout': '_sellerLogout',
            'product_detail/:productId': '_showProductDetailPage',
            'shop/:shopId': '_showShop',
            'search_result': '_showSearchResult'
        },

        initialize: function() {
            _.bindAll(
                this,

                '_showMainPage',
                '_showSellerSignupPage',
                '_showSellerLoginPage',
                '_sellerLogout',
                '_showProductDetailPage',
                '_showShop',
                '_showSearchResult'
            );
        },

        _showMainPage: function() {
            common.log( 'now in main page' );

            var hotShopListView = new HotShopListView();
            var hotProductListView = new HotProductListView();
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
        },

        _showShop: function( shopId ) {
            common.log( 'show detail' , 'yellow' );
            new ShopPageView({ shopId: shopId });
        },

        _showSearchResult: function() {
            common.log( 'search result' );
        },

        _showProductDetailPage: function( productId ) {
            console.dir( productId );
            new ProductDetailPageView({ product_id: productId });
        }
    });

    return Routes;
});

