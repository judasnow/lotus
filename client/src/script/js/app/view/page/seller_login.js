//page 
define ([

    "zepto",
    "backbone",

    "utility",

    "text!tpl/seller_login_page.mustache"

] , function ( $ , Backbone , aellerLoginPageTpl ) {
    "use strict";

    var SellerLoginView = Backbone.View.extend({

        className: "box",
        tagName: "div",

        tpl: sellerLoginPageTpl,

        initialize: function() {
            _.bindAll( this , "render" );

            this.render(); 
        },

        render: function() {
            this.$el = Mustache.to_html( this.tpl );
            utility.loadPage( this.$el )
        }
    });

    return SellerLoginView;
});





