define([
    'underscore',
    'backbone',
    'async',

    'v/stream.m',
    'v/detail.m'
], function(_, Backbone, async, Stream, Detail) {
    'use strict';

    var Routes_m= Backbone.Router.extend({
        routes: {
            'stream(/:classAId/:classBId)': '_showStreamPage',
            'detail/(:productId)': '_showDetailPage',
            '*path': '_showDefaultPage'
        },

        initialize: function() {
            _.bindAll(this, '_showStreamPage', '_showDetailPage', '_showDefaultPage');
        },

        _showDetailPage: function(productId) {
            new Detail({productId: productId});
        },

        _showDefaultPage: function() {
            this._showStreamPage(10000,10001);
        },

        _showStreamPage: function(classAId, classBId) {
            new Stream({
                classAId: classAId,
                classBId: classBId
            });
        }
    });

    return Routes_m;
});
