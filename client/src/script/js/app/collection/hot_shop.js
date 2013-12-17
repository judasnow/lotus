define([

    'zepto',
    'backbone',

    'm/shop',

    'config'

] , function(
    $ ,
    Backbone ,
    Shop,
    config
) {
    'use strict';

    var HotShopColl = Backbone.Collection.extend({
        model: Shop,

        initialize: function() {
            this.url = config.serverAddress + 'home_api/popular_shop/';
        }
    });

    return HotShopColl;
});


