define([

    "backbone",
    "config"

] , function( Backbone , config ) {
    "use strict";

    var User = Backbone.Model.extend({
        defaults: {
            email: "",
            password: "",
            role: ""
        },

        initialize: function() {
            this.url = config.serverAddress + "auth_api/user_info/";
        }
    });

    return User;
});
