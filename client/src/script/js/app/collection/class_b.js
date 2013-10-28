define([

    "backbone",
    "m/class_b"

] , function( Backbone , ClassB ) {
    "use strict";

    var ClassBColl = Backbone.Collection.extend( {

        model: ClassB,

        url: window.serverAddress + "/home_api/class_b"

    });

    return ClassBColl;
});

