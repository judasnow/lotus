define([
    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'c/product',

    'utilities/page',
    'config',
    'text!tpl/page/stream.m.mustache',
    'text!tpl/page/stream_item.m.mustache',
], function(
    $,
    _,
    Backbone,
    Mustache,

    ProductColl,

    page,
    config,
    streamTpl,
    streamItemTpl
) {
    'use strict';

    //根据分类显示商品信息
    var Stream_m = Backbone.View.extend({
        template: streamTpl,
        events: {
            'click .get-more': '_fetchMore',
            'click .stream-item': '_goToDetail'
        },
        initialize: function() {
            _.bindAll(this, '_fetch', '_addOne', 'render');

            if ( typeof arguments[0] === 'object' ) {
                this._classAId = arguments[0].classAId;
                this._classBId = arguments[0].classBId;
            }

            this._currentPage = 1;
            this._productColl = new ProductColl({
                url: config.serverAddress + '/home_api/category_products'
            })
            this.listenTo(this._productColl, 'add', this._addOne);

            this.render();
        },

        _goToDetail: function(event) {
            var $target = $(event.currentTarget);
            window.routes.navigate('detail/' + $target.attr('data-id'), {trigger: true});
        },

        _fetch: function(page) {
            this._productColl.fetch({
                data: {
                    class_a: this._classAId,
                    class_b: this._classBId,
                    page: page
                }
            });
        },

        _fetchMore: function() {
            //@TODO 如果没有 length 没有变 则证明没有商品了
            this._currentPage += 1;
            this._fetch(this._currentPage);
        },

        _addOne: function(item) {
            this.$items.append(Mustache.to_html(streamItemTpl, item.toJSON()));
        },

        render: function() {
            var that = this;
            this.$el.html(Mustache.to_html(this.template, '{}'));
            page.loadPage(this.$el, function() {
                that.$items = that.$el.find('.stream-box');
                that._fetch(that._currentPage)
            });
        }
    });

    return Stream_m;
});
