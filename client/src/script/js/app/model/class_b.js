define([

    'backbone'

] , function ( Backbone ) {
    'use strict';

    var ClassB = Backbone.Model.extend( {
        defaults: {
            class_b: '0',
            content: 'null'
        }
    });

    return ClassB;

});

