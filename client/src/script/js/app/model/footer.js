define([

    'zepto',
    'underscore',
    'backbone',

] , function( $ , _ , Backbone ) {
    'use strict';

    //对应于页脚视图
    //({
    // is_login::boolean
    //})
    var Footer = Backbone.Model.extend({
        defaults: {
        }
    });

    return Footer;
});

