define([

    'zepto',
    'backbone',
    'mustache',

    'v/hot_list_base',

    'c/hot_shop',

    'text!tpl/hot_shop_list_item.mustache'

] , function(
    $ ,
    Backbone,
    Mustache,

    HotListBase,

    HotShopColl,

    HotShopListItemTpl
) {
    'use strict';

    var HotShopList = HotListBase.extend({
        initialize: function() {
            //base 里面没有 events 属性
            this.events = {
                'click .item_box': '_goShopPage'
            };

            _.bindAll( this , '_goShopPage' );

            this._baseInit( 'hot_shop_recommend' , new HotShopColl() , HotShopListItemTpl );
        },

        _goShopPage: function( e ) {
            var $currEl = $( e.currentTarget );
            var showId = $currEl.attr( 'data-attr' );

            window.routes.navigate( 'shop/' + showId , {trigger: true} );
        }

    });

    return HotShopList;
});

