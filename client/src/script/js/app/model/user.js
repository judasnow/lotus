define([

    "backbone",
    "config"

] , function( Backbone , config ) {
    "use strict";

    var User = Backbone.Model.extend({

        defaults: {
            user_id: "",
            email: "",
            user_role: ""
        },

        initialize: function() {
            this.url = config.serverAddress + "auth_api/user_info/";
        }
    });

    return User;
});
