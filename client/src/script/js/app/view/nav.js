define([
    'zepto',
    'backbone',
    'mustache',

    'm/nav',

    'v/categories_browse',
    'v/dropdown',
    'v/add_new_product_dialog',

    'utilities/common',

    'text!tpl/nav.mustache',
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

    navTpl,
    objectUserDropdownTpl
 ) {
    'use strict';

    var Nav = Backbone.View.extend({

        el: '#nav',
        template: navTpl,

        events: {
            'click .categories-browse-btn': 'toggleCategoriesBrowse',
            'click .nav-user': 'showObjectUserDropDown'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_getEls',
                'render',
                'toggleCategoriesBrowse',
                'showObjectUserDropDown',
                'showLoading',
                'hideLoading'
            );

            this.model = new NavModel();
            this.listenTo( this.model, 'change', this.render );

            this.render();

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
        //{{{
            this.$el.html( Mustache.to_html( this.template, this.model.toJSON() ) );
            this._getEls();
        }//}}}

    });

    return Nav;
});

