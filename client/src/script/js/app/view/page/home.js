define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'async',

    'v/search',
    'v/hot_shop_list',
    'v/hot_product_list',
    'v/stat_statistics',

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
    statStatisticsView,

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
                    window.e.trigger( 'load_home_page' );
                },
                function( cb ) {
                    //@XXX 內聚性有問題
                    new SearchView({ $el: that.$el.find( '#search-box' ) });
                    new HotShopListView();
                    new HotProductListView();
                    new statStatisticsView();

                    cb();
                }
            ]);
        }
    });

    return HomePageView;
});

