define([

    'zepto',
    'backbone',

    'utilities/common',

    'config'

] , function(
    $ ,
    Backbone ,

    common,
    config 
) {
    'use strict';

    var Shop = Backbone.Model.extend({
        initialize: function() {
            this.url = config.serverAddress + 'shop_api/info/';
        }
    });

    return Shop;
});

