// 將需要渲染的信息放到 model 中
define([
    'zepto',
    'backbone',
    'mustache',

    'm/nav',
    'v/categories_browse',
    'v/dropdown',
    'v/add_new_product_dialog',

    'utilities/common',

    'text!tpl/nav/username.mustache',
    'text!tpl/nav/object_user_dropdown.mustache'

] , function(

    $,
    Backbone,
    Mustache,

    NavModel,
    CategoriesBrowseView,
    DropdownView,
    AddNewProductDialog,

    common,

    usernameTpl,
    objectUserDropdownTpl

 ) {
    'use strict';

    var Nav = Backbone.View.extend({

        el: '#nav',

        events: {
            'click .categories_browse_btn': 'toggleCategoriesBrowse',
            'click .nav_user': 'showObjectUserDropDown'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_getEls',
                'toggleCategoriesBrowse',
                'showObjectUserInfo',
                'showObjectUserDropDown',
                'showLoading',
                'hideLoading'
            );

            this._model = new NavModel();

            this._getEls();
            //渲染分類列表
            this._categoriesBrowseView = new CategoriesBrowseView();
        },//}}}

        _getEls: function() {
        //{{{
            this._$userinfo = this.$el.find( '.userinfo' );
            this._$loading = this.$el.find( '.loading' );
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
            var id = 'object_user_dropdown';
            var offset = $(e.currentTarget).offset();

            //如果還沒有渲染該部分 則渲染之 否則只是顯示
            if( typeof this.dropdownView === 'undefined' ) {

                var e = {
                    'click .add_new_product': function() {
                        //@XXX 实例化的位置在哪里比较好？
                        window.addNewProductDialogView = window.addNewProductDialogView || new AddNewProductDialog();
                        window.addNewProductDialogView.toggle();
                    }
                };

                this.dropdownView = new DropdownView( offset, 'object_user_dropdown', objectUserDropdownTpl, e );
            }

            this.dropdownView.$el.toggle();
        },//}}}

        toggleCategoriesBrowse: function() {
        //{{{
            this._categoriesBrowseView.toggle();
        },//}}}

        render: function() {
            
        }

    });

    return Nav;
});

