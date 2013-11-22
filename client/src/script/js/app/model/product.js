define([

    'zepto',
    'backbone',

    'config',

    'utilities/common'

] , function( $ , Backbone , config , common ) {
    'use strict';

    var Product = Backbone.Model.extend({
        initialize: function() {
            this.urlRoot = config.serverAddress + 'product_api/new_product/?session_id=' + common.getSessionId();
        }
    });

    return Product;
});

