define([

    "backbone",
    "m/class_b",

    "config"

] , function( Backbone , ClassB , config ) {
    "use strict";

    var ClassBColl = Backbone.Collection.extend({

        model: ClassB,

        initialize: function( class_a_id ) {
            this.url = config.serverAddress + "/home_api/class_b/?class_a_id=" + class_a_id;
        }

    });

    return ClassBColl;
});

