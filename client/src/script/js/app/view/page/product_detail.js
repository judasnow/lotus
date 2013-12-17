define([

    'zepto',
    'backbone',
    'mustache',
    'async',

    'm/shop',
    'm/product',

    "utilities/page",

    'text!tpl/page/product_detail.mustache',
    'text!tpl/page/product_detail_page_detail_image_item.mustache'

] , function(

    $,
    Backbone,
    Mustache,
    async,

    Shop,
    Product,

    page,

    ProductDetailPageTpl,
    DetailImageItemTpl
) {
    'use strict';

    var ProductDetailPageView = Backbone.View.extend({
        id: 'product-detail',
        className: 'box',

        template: ProductDetailPageTpl,

        initialize: function( args ) {
        //{{{
            if( isNaN( args.product_id ) ) {
                throw new Error( 'param invalid' );
            }

            _.bindAll( this, '_renderDetailImage', 'render' );

            var productId = args.product_id;
            this._model = new Product({ id: productId });
            this.listenTo( this._model, 'change', this.render );

            this._model.fetch();
        },//}}}

        //根据 model 中的 detail image 渲染相应的列表文档
        //( void ) => string
        _renderDetailImage: function() {
        //{{{
            var detailImages = this._model.get( 'product_detail_image_url' );
            var html = '';

            if( detailImages.length !== 0 ) {
                html = _.reduce( detailImages , function( html , imageUrl ) {
                    return html + Mustache.to_html( DetailImageItemTpl, {product_detail_image_url: imageUrl} );
                }, '' );
            }

            return html;
        },//}}}

        render: function() {
        //{{{
            var that = this;
            var shopId = this._model.get( 'shop_id' );
            var shop = new Shop();

            async.series([
                function( cb ) {
                    shop.fetch({
                        data: {shop_id: shopId},
                        success: function( shop ) {
                            that._model.set( 'shopInfo', shop.toJSON() );
                            cb();
                        }
                    });
                },

                function( cb ) {
                    that.$el.html(
                        Mustache.to_html(
                            that.template,
                            that._model.toJSON(),
                            {
                                detail_image_list: that._renderDetailImage()
                            }
                        )
                    );

                    cb();
                    page.loadPage( that.$el );
                }
            ]);

            return this;
        }//}}}
    });

    return ProductDetailPageView;
});

