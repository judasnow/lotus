define([

    "zepto",
    "backbone",
    "mustache",

    "v/categories_browse",
    "v/dropdown",

    "utilities/common",

    "text!tpl/nav/username.mustache",
    "text!tpl/nav/object_user_dropdown.mustache"

] , function(
    $ ,
    Backbone ,
    Mustache,

    CategoriesBrowseView,
    DropdownView,
    common,

    usernameTpl,
    objectUserDropdownTpl
 ) {
    "use strict";

    var Nav = Backbone.View.extend({

        el: "#nav",

        events: {
            "click .categories_browse_btn": "toggleCategoriesBrowse",
            "click .nav_user": "showObjectUserDropDown"
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this ,

                "toggleCategoriesBrowse",
                "_getEls",
                "showObjectUserInfo",
                "showObjectUserDropDown",
                "showLoading",
                "hideLoading"
            );

            this._getEls();
            this._categoriesBrowseView = new CategoriesBrowseView();
        },//}}}

        _getEls: function() {
        //{{{
            this._$userinfo = this.$el.find( ".userinfo" );
            this._$loading = this.$el.find( ".loading" );
        },//}}}

        showLoading: function() {
        //{{{
            this._$loading.show();
        },//}}}

        hideLoading: function() {
        //{{{
            this._$loading.hide();
        },//}}}

        showObjectUserInfo: function() {
        //{{{
            this._$userinfo.html( Mustache.to_html( usernameTpl , window.objectUser.toJSON() ) );
        },//}}}

        //用户信息配套的下拉菜单
        showObjectUserDropDown: function( e ) {
        //{{{
            var id = "object_user_dropdown";
            var offset = $(e.currentTarget).offset();

            if( typeof this.dropdownView === "undefined" ) {
                this.dropdownView = new DropdownView( offset , "object_user_dropdown" , objectUserDropdownTpl );
                this.dropdownView.$el.on( "") = { "click": function(){ alert( 123 )} }
            }

            this.dropdownView.$el.toggle();
        },//}}}

        toggleCategoriesBrowse: function() {
        //{{{
            this._categoriesBrowseView.toggle();
        }//}}}

    });

    return Nav;
});

