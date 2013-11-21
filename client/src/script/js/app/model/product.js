define([

    'zepto',
    'backbone',

    'config'

] , function( $ , Backbone , config ) {
    'use strict';

    var Product = Backbone.Model.extend({
        initialize: function() {
            this.urlRoot = config.serverAddress + 'product_api/new_product/';
        }
    });

    return Product;
});

