define([

    "zepto",
    "mustache",
    "backbone",

    "utility",

    "text!tpl/seller_signup.mustache"

] , function( 
    $,
    Mustache,
    Backbone,
    utility,

    SellerSignupTpl 
 ) { 
    "use strict";

    var SellerSignupView = Backbone.View.extend({

        className: "box",
        tagName: "div",

        tpl: SellerSignupTpl,

        initialize: function() {
            _.bindAll( this , "render" );

            this.render();
        },

        render: function() {
            this.$el = Mustache.to_html( this.tpl );
            utility.loadPage( this.$el );
        }
    });

    return SellerSignupView;
});
