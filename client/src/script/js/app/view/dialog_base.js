//@TODO
define([

    "zepto",
    "backbone"

], function( $ , Backbone ) {
    "use strict";

    var DialogViewBase = Backbone.View.extend({

        events: {
            "click .close": "closeDialog",
        },

        _baseInit: function() {
            _.bindAll( this , "closeDialog" , "showDialog" , "render" );

            this.render();
        },

        closeDialog: function() {
            this.$el.hide();
        },

        showDialog: function() {
            this.$el.show();
        },

        render: function() {
            this.$el.html( this.tpl );

            $( "body" ).append( this.$el );
        }

    });

    return DialogViewBase;
});

