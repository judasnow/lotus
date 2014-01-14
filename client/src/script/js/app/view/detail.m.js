define([
    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'm/product',

    'utilities/page',
    'config',
    'text!tpl/page/detail.m.mustache',
], function(
    $,
    _,
    Backbone,
    Mustache,

    Product,

    page,
    config,
    detailTpl
) {
    'use strict';

    //根据分类显示商品信息
    var Detail_m = Backbone.View.extend({
        template: detailTpl,
        initialize: function() {
            if (typeof arguments[0] === 'object' ) {
                this._productId = arguments[0].productId;
            }

            this._product = new Product({id: this._productId});
            this.listenTo(this._product, 'change', this.render);

            this._product.fetch()
        },

        render: function() {
            var that = this;

            var imgs = [];
            this._product.get('product_detail_image_url').forEach(function(item) {
                imgs.push({'url': item});
            });
            this._product.set('detail_imgs', imgs);

            this.$el.html(Mustache.to_html(this.template, this._product.toJSON()));

            page.loadPage(this.$el, function() {

            });
        }
    });

    return Detail_m;
});
