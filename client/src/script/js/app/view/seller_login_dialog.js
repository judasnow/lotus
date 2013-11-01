define([

    "backbone",

    "text!tpl/seller_login_dialog.mustache"

] , function( 
    Backbone , 

    sellerLoginDialogTpl
) {
    "use strict";

    var SellerLoginDialogView = Backbone.View.extend({

        tagName: "div",
        className: "dialog_box",
        id: "seller_login_dialog",

        tpl: sellerLoginDialogTpl,

        initialize: function() {
            
        },

        render: function() {
            
        }
    });

    return SellerLoginDialogView;
});
