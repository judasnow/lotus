define([
    'underscore',
    'backbone',
    'async',

    'utilities/auth',
    'utilities/common',

    'v/page/home',
    'v/page/seller_signup',
    'v/page/seller_login',
    'v/page/edit_product',

    'v/page/shop_apply',
    'v/page/apply_result_search',

    'v/page/shop',
    'v/page/product_detail',
    'v/page/search_result',
    'v/page/product_list_by_class',
    'v/page/not_found'

] , function(

    _,
    Backbone,
    async,

    auth,
    common,

    HomePageView,
    SellerSignupView,
    SellerLoginView,
    EditProductPageView,

    ShopApplyPageView,
    ApplyResultSearchPageView,

    ShopPageView,
    ProductDetailPageView,
    SearchResultPageView,
    ProductListByClassView,
    NotFoundPageView
) {
    'use strict';

    var Routes = Backbone.Router.extend({

        routes: {
            '': '_showMainPage',
            'main': '_showMainPage',
            'seller_signup': '_showSellerSignupPage',
            'seller_login': '_showSellerLoginPage',
            'seller_logout': '_sellerLogout',
            'shop_apply': '_showShopApplyPage',
            'apply_result_search': '_showApplyResultSearch',
            'product_detail/:productId': '_showProductDetailPage',
            'add_new_product': '_addNewProduct',

            'shop/:shopId(/:currentPage)': '_showShop',
            'my_shop(/:current_page)': '_myShop',

            'search_result(/:q/:p)(/)': '_showSearchResult',

            'product_list_by_class/:classA/:classB(/:p)(/)': '_showProductListByClass',
            'page_not_found': '_showNotFoundPage',

            //default page
            '*path': '_showNotFoundPage'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_showMainPage',
                '_showSellerSignupPage',
                '_showSellerLoginPage',
                '_showShopApplyPage',
                '_sellerLogout',
                '_showProductDetailPage',
                '_showShop',
                '_showSearchResult'
            );
        },//}}}

        _showMainPage: function() {
        //{{{
            new HomePageView();
        },//}}}

        _showSellerSignupPage: function() {
        //{{{
            new SellerSignupView();
        },//}}}

        _showSellerLoginPage: function() {
        //{{{
            new SellerLoginView();
        },//}}}

        _showShopApplyPage: function() {
        //{{{
            new ShopApplyPageView();
        },//}}}

        _showApplyResultSearch: function() {
        //{{{
            new ApplyResultSearchPageView();
        },//}}}

        _sellerLogout: function() {
        //{{{
            auth.doLogout( function() {
                window.routes.navigate( 'main', {trigger: true} );
            });
        },//}}}

        _showShop: function( shopId , currentPage ) {
        //{{{
            var isSelfShop = false;
            if( window.objectUser.get( 'shop_id' ) === shopId ) {
                isSelfShop = true;
            }
            new ShopPageView({
                shopId: shopId,
                currentPage: currentPage,
                isSelfShop: isSelfShop
            });
        },//}}}

        _myShop: function( currentPage ) {
            if( ! common.isLogin() ) {
                window.routes.navigate('seller_login', {trigger: true});
            } else {
                new ShopPageView({
                    shopId: window.objectUser.get( 'shop_id' ),
                    currentPage: currentPage,
                    isSelfShop: true
                });
            }
        },

        _showSearchResult: function( q ) {
        //{{{
            new SearchResultPageView({q: q});
        },//}}}

        _showProductDetailPage: function( productId ) {
        //{{{
            new ProductDetailPageView({ product_id: productId });
        },//}}}

        _showProductListByClass: function( classA, classB, currentPage ) {
        //{{{
            new ProductListByClassView({
                class_a: classA,
                class_b: classB,
                current_pagep: currentPage
            });
        },//}}}

        _addNewProduct: function() {
            new EditProductPageView();
        },

        _editProduct: function( productId ) {
            new EditProductPageView({ productId: productId });
        },

        _showNotFoundPage: function() {
        //{{{
            new NotFoundPageView();
        }//}}}
    });

    return Routes;
});


