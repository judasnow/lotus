define([

    'zepto',
    'backbone',
    'mustache',
    'q',

    'm/shop',
    'v/product_list',
    'c/product',
    'v/product_list_page',

    'utilities/page',
    'utilities/common',

    'config',

    'text!tpl/page/shop.mustache'

] , function(

    $,
    Backbone,
    Mustache,
    Q,

    Shop,
    ProductListView,
    ProductColl,
    ProductListPageView,

    page,
    common,

    config,

    shopPageTpl 
) {
    'use strict';

    // shop model -> render page -> product_list
    var ShopPageView = Backbone.View.extend({

        id: 'shop-page',
        className: 'box',

        template: shopPageTpl,

        // ({ 
        //  shopId::number,
        //  currentPage::number,
        //  isSelfShop::boolean
        // }) => void
        initialize: function( args ) {
        //{{{
            this.events = {};

            if ( isNaN( args.shopId ) ) {
                throw new Error( 'param invalid, shopId`s type must be number' );
            }

            if( typeof args.currentPage === 'undefined' || args.currentPage === null || isNaN( args.currentPage ) ) {
                // 页码设置的不合法 默认为 1
                this._currentPage = 1;
            } else {
                this._currentPage = parseInt( args.currentPage );
            }

            var that = this;
            var shopId = args.shopId;

            // 表明是当前用户自己的店铺 允许用户修改商品信息
            this._isSelfShop = false;
            if ( typeof args.isSelfShop === 'boolean' && args.isSelfShop === true ) {
                this._isSelfShop = true;
            }

            _.bindAll(
                this ,

                '_renderProductList',
                'render'
            );

            this._model = new Shop({
                shop_id: shopId
            });
            this._model.fetch({
                data: {
                    shop_id: this._model.get( 'shop_id' )
                },

                //成功获取店铺信息之后才有继续下去的必要
                //@TODO 使用 Q 改写之
                success: function() {
                    that.render();
                },

                //@TODO 没有相应商铺信息的时候 显示 404 页面
                error: function( m ) {
                    window.routes.navigate( '/page_not_found' , {trigger: true} );
                }
            });
        },//}}}

        //@TODO 存在重构的空间
        _renderProductList: function() {
        //{{{
            this._productColl = new ProductColl({
                url: config.serverAddress + 'shop_api/products/'
            });

            this._productListView = new ProductListView({
                $el: this.$el.find( '.shop-page-product-list ul' ),
                coll: this._productColl
            });

            var pageNoIndex = 2;
            if ( this._isSelfShop === true ) {
                pageNoIndex = 1;
            }

            //获取并显示分页信息
            this._pageView = new ProductListPageView({
                $el: this.$el.find( '.product-list-pager-list' ),
                getUrl: 'shop_api/product_page_count/',
                currentPage: this._currentPage,
                pageNoIndex: pageNoIndex,
                options: {
                    shop_id: this._model.get( 'shop_id' )
                }
            });

            //获取指定页码的信息
            this._productListView.getListByPage( this._currentPage, {
                shop_id: this._model.get( 'shop_id' )
            });

        },//}}}

        render: function() {
        //{{{
            var renderProductList = this._renderProductList;

            this.$el.html( Mustache.to_html( this.template , this._model.toJSON() ) );
            page.loadPage( this.$el, function() {
                renderProductList();
            });
        }//}}}
    });

    return ShopPageView;
});

