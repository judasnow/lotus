define([

    'backbone',
    'm/class_b',

    'config'

] , function( Backbone , ClassB , config ) {
    'use strict';

    var ClassBColl = Backbone.Collection.extend({

        model: ClassB,

        initialize: function() {
            this.url = config.serverAddress + '/home_api/class_b/';
        }

    });

    return ClassBColl;
});

