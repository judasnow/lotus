define([

    'zepto',
    'backbone',
    'mustache',

    'm/product',

    "utilities/page",

    'text!tpl/page/product_detail.mustache'

] , function(
    $,
    Backbone,
    Mustache,

    Product,

    page,

    ProductDetailPageTpl
) {
    'use strict';

    var ProductDetailPageView = Backbone.View.extend({
        id: 'product-detail',
        className: 'box',

        template: ProductDetailPageTpl,

        initialize: function( args ) {
            if( isNaN( args.product_id ) ) {
                throw new Error( 'param invalid' );
            }

            var productId = args.product_id;
            this._model = new Product({ id: productId });

            this.listenTo( this._model , 'change' , this.render );

            this._model.fetch();
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template , this._model.toJSON() ) );
            page.loadPage( this.$el );

            return this;
        }
    });

    return ProductDetailPageView;
});

