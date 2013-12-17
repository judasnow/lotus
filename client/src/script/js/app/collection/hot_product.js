define([

    'zepto',
    'backbone',

    'm/product',

    'config'

] , function(
    $ ,
    Backbone ,
    Product ,
    config
) {
    'use strict';

    var HotProduct = Backbone.Collection.extend({
        model: Product,

        initialize: function() {
            this.url = config.serverAddress + 'home_api/popular_products/';
        }
    });

    return HotProduct;
});

