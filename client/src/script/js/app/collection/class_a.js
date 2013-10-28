define([

    "backbone",
    "m/class_a",

    "config"

] , function( Backbone , ClassA , config ) {
    "use strict";

    var ClassAColl = Backbone.Collection.extend( {

        model: ClassA,

        url: config.serverAddress + "/home_api/class_a",

        initialize: function () {
            
        }

    });

    return ClassAColl;
});
