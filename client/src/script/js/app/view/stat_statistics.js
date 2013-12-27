define([

    'zepto',
    'backbone',

    'm/stat_statistics'

] , function( $, Backbone, statStatistics ) {
    'use strict';

    var statStatisticsView = Backbone.View.extend({
        el: '#stat-statistics',

        initialize: function() {
            _.bindAll( this, 'render' );
            var that = this;
            this._model = new statStatistics();
            this.listenTo( this._model, 'change', this.render );

            this._$productCount = this.$el.find( '.product-count' );
            this._$shopCount = this.$el.find( '.shop-count' );

            that._model.fetch();

            var statInterId = setInterval( function() {
                that._model.fetch();
            }, 60000 );

            $( window ).one( 'hashchange', function() {
                clearInterval( statInterId );
            });

        },

        render: function() {
            var productNum = this._model.get( 'product_num' );
            var shopNum = this._model.get( 'shop_num' );

            if( productNum === null ) {
                productNum = 0;
            }
            if( shopNum === null ) {
                shopNum = 0;
            }

            this._$productCount.text( productNum );
            this._$shopCount.text( shopNum );
        }
    });

    return statStatisticsView;
});

