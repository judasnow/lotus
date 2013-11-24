define([

    "zepto",
    "backbone",
    "mustache",

    "v/categories_browse",
    "v/dropdown",
    "v/add_new_product_dialog",

    "utilities/common",

    "text!tpl/nav/username.mustache",
    "text!tpl/nav/object_user_dropdown.mustache"

] , function(

    $,
    Backbone,
    Mustache,

    CategoriesBrowseView,
    DropdownView,
    AddNewProductDialog,

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

            //@XXX 这样的设置是不是显得有点碎片化了？
            //是不是应该将 DropdownView 派生一下？
            if( typeof this.dropdownView === "undefined" ) {
                var e = {
                    "click .add_new_product": function() {
                        //@XXX 实例化的位置在哪里比较好？
                        window.addNewProductDialogView = window.addNewProductDialogView || new AddNewProductDialog();
                        window.addNewProductDialogView.toggle();
                    }
                };

                this.dropdownView = new DropdownView( offset , "object_user_dropdown" , objectUserDropdownTpl , e );
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

