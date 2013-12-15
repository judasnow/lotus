define([

    'zepto',
    'backbone',

    'v/hot_list_base',

    'c/hot_product',

    'text!tpl/hot_product_list_item.mustache'

] , function(
    $ ,
    Backbone ,

    HotListBase,

    HotProductColl,

    HotProductListItemTpl
) {
    'use strict';

    var HotProductListView = HotListBase.extend({
        initialize: function() {

            //base 里面没有 events 属性
            this.events = {
                'click .item_box': '_goProductDetailPage'
            };

            _.bindAll( this , '_goProductDetailPage' );

            this._baseInit( 'hot_product_recommend', new HotProductColl(), HotProductListItemTpl );
        },

        _goProductDetailPage: function( e ) {
            var $currEl = $( e.currentTarget );
            var productId = $currEl.attr( 'data-attr' );

            window.routes.navigate( 'product_detail/' + productId , {trigger: true} );
        }
    });

    return HotProductListView;
});
