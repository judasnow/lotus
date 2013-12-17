define([
    'zepto',
    'backbone',
    'mustache',

    'm/nav',

    'v/categories_browse',
    'v/dropdown',

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
        showObjectUserDropDown: function( event ) {
        //{{{
            var id = 'object_user_dropdown';
            var offset = $(event.currentTarget).offset();
            var $dropdown = null;

            //如果還沒有渲染該部分 則渲染之 否則只是顯示
            if( typeof this.dropdownView === 'undefined' ) {
                var event = {};
                this.dropdownView = new DropdownView( offset, 'object_user_dropdown', objectUserDropdownTpl, event );
                $dropdown = this.dropdownView.$el;
                $( window ).on( 'click', function( event ) {
                    var $targetEl = $( event.target );

                    //不在目標區域的 才執行隱藏操作
                    if( (! $targetEl.hasClass( 'nav-user' ) && $targetEl.parents( '.nav-user' ).length === 0)  &&
                        (! $targetEl.hasClass( 'dropdown' ) && $targetEl.parents( '.dropdown' ).length === 0) ) {
                        common.log( $targetEl.attr( 'class' ) );
                        common.log( $targetEl.parents( '.dropdown' ).length );
                        $dropdown.hide();
                    }
                });
            } else {
                $dropdown = this.dropdownView.$el;
            }

            $dropdown.toggle();
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

