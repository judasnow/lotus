define([
    'zepto',
    'backbone',

    'm/product',

] , function( $ , Backbone , Product ) {
    'use strict';

    var ProductColl = Backbone.Collection.extend({
        model: Product,

        initialize: function( args ) {
            this.url = args.url;
        }
    });

    return ProductColl;
});

