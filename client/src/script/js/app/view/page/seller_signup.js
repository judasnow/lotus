//page
define([

    "zepto",
    "mustache",
    "backbone",

    "utilities/page",

    "text!tpl/page/seller_signup.mustache"

] , function(

    $,
    Mustache,
    Backbone,
    page,

    SellerSignupTpl 
 ) { 
    "use strict";

    var SellerSignupView = Backbone.View.extend({

        className: "box",
        tagName: "div",

        events: {
            "click .submit": "doReg"
        },

        tpl: SellerSignupTpl,

        initialize: function() {
            _.bindAll( this , "render" );

            this.render();
        },

        doReg: function() {
            alert( 123 )
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.tpl ) );
            page.loadPage( this.$el );
        }
    });

    return SellerSignupView;
});

