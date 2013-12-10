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
        //  shop_id::number,
        //  current_page::number
        // }) => void
        initialize: function( args ) {
        //{{{
            if( isNaN( args.shop_id ) ) {
                throw new Error( 'param invalid' );
            }

            if( isNaN( args.current_page ) ) {
                this._currentPage = 1;
            } else {
                this._currentPage = parseInt( args.current_page );
            }

            var that = this;
            var shopId = args.shop_id;

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

            //获取并显示分页信息
            this._pageView = new ProductListPageView({
                $el: this.$el.find( '.product-list-pager-list' ),
                getUrl: 'shop_api/product_page_count/',
                currentPage: this._currentPage,
                options: {
                    shop_id: this._model.get( 'shop_id' )
                }
            });

            //获取第一页信息 如果存在的话
            this._productListView.getListByPage( 1 , {
                shop_id: this._model.get( 'shop_id' )
            });
        },//}}}

        render: function() {
        //{{{
            this.$el.html( Mustache.to_html( this.template , this._model.toJSON() ) );
            page.loadPage( this.$el );

            this._renderProductList();
        }//}}}
    });

    return ShopPageView;
});

