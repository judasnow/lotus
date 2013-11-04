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
            if( typeof this._sellerLoginDialogView === "undefined" ) {
                this._sellerLoginDialogView = new SellerLoginDialogView();
            } else {
                this._sellerLoginDialogView.showDialog();
            }
        },

    });

    return FooterView;
})
