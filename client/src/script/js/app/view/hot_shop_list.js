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
            this._baseInit( 'hot_shop_recommend' , new HotShopColl() , HotShopListItemTpl );
        }
    });

    return HotShopList;
});

