define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'async',

    'v/search',
    'v/hot_shop_list',
    'v/hot_product_list',

    'utilities/page',
    'text!tpl/page/home.mustache'

] , function(

    $ ,
    _ ,
    Backbone,
    Mustache,
    async,

    SearchView,
    HotShopListView,
    HotProductListView,

    page,

    homePageTpl
) {
    'use strict';

    var HomePageView = Backbone.View.extend({
        id: 'home-page',
        className: 'box',

        template: homePageTpl,

        initialize: function( args ) {
            this.render();
        },

        render: function() {
            var that = this;
            this.$el.html( Mustache.to_html( this.template ) );

            async.series([
                function( cb ) {
                    page.loadPage( that.$el , cb );
                },
                function( cb ) {
                    new SearchView({ $el: that.$el.find( '#search_box' ) });
                    new HotShopListView();
                    new HotProductListView();

                    cb();
                }
            ]);
        }
    });

    return HomePageView;
});

