define([

    'zepto',
    'underscore',
    'backbone',

] , function( $ , _ , Backbone ) {
    'use strict';

    var NavModel = Backbone.Model.extend({
        defaults: {
            // It is a json but Backbone Model
            objectUserInfo: {}
        }
    });

    return NavModel;
});

