define([
    'zepto',
    'underscore',
    'backbone',
] , function( $ , _ , Backbone ) {
    'use strict';

    var SysNoticeModel = Backbone.Model.extend({
        defaults: {
            msg: '（¯﹃¯）'
        },
        initialize: function() {
            
        }
    });

    return SysNoticeModel;
});

