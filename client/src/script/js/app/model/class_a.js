define([

    "backbone"

] , function ( Backbone ) {
    "use strict";

    var ClassA = Backbone.Model.extend( {
        defaults: {
            class_a: "0",
            content: "null"
        }
    });

    return ClassA;

});
