define([
    'zepto',
    'underscore',
    'backbone',
] , function( $ , _ , Backbone ) {
    'use strict';

    var NavModel = Backbone.Model.extend({
        defaults: {
            objectUserinfo: {}
        }
    });

    return NavModel;
});

