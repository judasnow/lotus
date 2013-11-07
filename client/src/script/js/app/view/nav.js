define([

    "zepto",
    "backbone",
    "mustache",

    "v/categories_browse",

    "text!tpl/nav_username.mustache"

] , function(
    $ ,
    Backbone ,
    Mustache,

    CategoriesBrowseView,

    usernameTpl
 ) {
    "use strict";

    var Nav = Backbone.View.extend({

        el: "#nav",

        events: {
            "click .categories_browse_btn": "toggleCategoriesBrowse"
        },

        initialize: function() {
            _.bindAll(
                this ,
                "toggleCategoriesBrowse" ,
                "_getEls" ,
                "showObjectUserInfo" ,
                "showLoading" ,
                "hideLoading"
            );

            this._getEls();
            this._categoriesBrowseView = new CategoriesBrowseView();
        },

        _getEls: function() {
            this._$userinfo = this.$el.find( ".userinfo" );
            this._$loading = this.$el.find( ".loading" );
        },

        showLoading: function() {
            this._$loading.show();
        },

        hideLoading: function() {
            this._$loading.hide();
        },

        showObjectUserInfo: function() {
            this._$userinfo.html( Mustache.to_html( usernameTpl , window.objectUser.toJSON() ) );
        },

        toggleCategoriesBrowse: function() {
            this._categoriesBrowseView.toggle();
        },

    });

    return Nav;
});
