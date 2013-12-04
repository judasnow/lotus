define([
    'underscore',
    'backbone',
    'async',

    'utilities/auth',
    'utilities/common',

    'v/page/home',
    'v/page/seller_signup',
    'v/page/seller_login',
    'v/page/shop',
    'v/page/product_detail',
    'v/page/search_result',

    'v/hot_shop_list',
    'v/hot_product_list'

] , function(
    _,
    Backbone,
    async,

    auth,
    common,

    HomePageView,
    SellerSignupView,
    SellerLoginView,
    ShopPageView,
    ProductDetailPageView,
    SearchResultPageView,

    HotShopListView,
    HotProductListView
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

            'shop/:shopId(/:currentPage)': '_showShop',

            'search_result(/:q/:p)': '_showSearchResult'
        },

        initialize: function() {
        //{{{
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
        },//}}}

        _showMainPage: function() {
        //{{{
            async.series([
                function( cb ){
                    new HomePageView({ cb: cb });
                },
                function( cb ){ 
                    new HotShopListView();
                    new HotProductListView();

                    cb();
                }
            ]);
        },//}}}

        _showSellerSignupPage: function() {
        //{{{
            new SellerSignupView();
        },//}}}

        _showSellerLoginPage: function() {
        //{{{
            new SellerLoginView();
        },//}}}

        _sellerLogout: function() {
        //{{{
            auth.doLogout( function() {
                window.routes.navigate( 'main' , {trigger: true} );
            });
        },//}}}

        _showShop: function( shopId , currentPage ) {
        //{{{
            new ShopPageView({ shop_id: shopId , current_page: currentPage});
        },//}}}

        _showSearchResult: function( q ) {
        //{{{
            new SearchResultPageView({q: q});
        },//}}}

        _showProductDetailPage: function( productId ) {
        //{{{
            new ProductDetailPageView({ product_id: productId });
        }//}}}

    });

    return Routes;
});

