define([

    "backbone",

    "v/seller_login_dialog"

] , function(

    Backbone,

    SellerLoginDialogView

) {
    "use strict";

    var FooterView = Backbone.View.extend({

        el: "#footer .box",

        events: {
            "click .seller_login": "showSellerLoginDialog"
        },

        initialize: function() {
            
        },

        showSellerLoginDialog: function() {
            var sellerLoginDialogView = new SellerLoginDialogView();
        },

    });

    return FooterView;
})
