define([

    'zepto',
    'backbone',
    'mustache',

    'm/shop',
    'v/product_list',
    'c/product',

    'utilities/page',

    'config',

    'text!tpl/page/shop.mustache'

] , function(

    $,
    Backbone,
    Mustache,

    Shop,
    ProductListView,
    ProductColl,

    page,

    config,

    shopPageTpl 
) {
    'use strict';

    var ShopPageView = Backbone.View.extend({
        id: 'shop-page',
        className: 'box',

        template: shopPageTpl,

        initialize: function( args ) {
            if( isNaN( args.shop_id ) ) {
                throw new Error( 'param invalid' );
            }

            var shopId = args.shop_id;

            _.bindAll( this , 'render' );

            this._model = new Shop({shop_id: shopId});
            this.listenTo( this._model , 'change' , this.render );

            //这里的思路就是由 给 productListView 推送不同的 coll 以显示不同的结果
            this._productColl = new ProductColl({
                url: config.serverAddress + 'shop_api/products/'
            });
            this._productColl.fetch({
                data: {
                    shop_id: this._model.get( 'shop_id' ),
                    page: 1
                }
            });

            this._model.fetch({
                data: {
                    shop_id: this._model.get( 'shop_id' )
                }
            });

        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template , this._model.toJSON() ) );
            page.loadPage( this.$el );

            return this;
        }
    });

    return ShopPageView;
});

