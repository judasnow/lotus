define([
    'zepto',
    'backbone',

    'config'
] , function(
    $,
    Backbone,

    config
) {
    'use strict';

    var statStatistics = Backbone.Model.extend({
        defaults: {
            shop_num: 0,
            product_num: 0
        },

        initialize: function() {
            this.url = config.serverAddress + 'home_api/pands_count/';
        }
    });

    return statStatistics;
});

