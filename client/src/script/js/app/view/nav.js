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

    var NavView = Backbone.View.extend({

        el: '#nav',
        template: navTpl,

        events: {
            'click .categories-browse-btn': 'toggleCategoriesBrowse'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_getEls',
                'render',
                'toggleCategoriesBrowse',
                'showLoading',
                'hideLoading'
            );

            this.model = new NavModel();
            this.listenTo( this.model, 'change', this.render );

            this.render();

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

        toggleCategoriesBrowse: function() {
        //{{{
            this._categoriesBrowseView.toggle();
        },//}}}

        render: function() {
        //{{{
            this.$el.html( Mustache.to_html( this.template, this.model.toJSON() ) );

            this._getEls();

            this._categoriesBrowseView = new CategoriesBrowseView();

            // 用户鼠标移动到 host 上的触发元素之后 初始化
            // dropdown
            this.$el.find( '.nav-user' ).one( 'mouseover', function( event ) {
                this._dropdownView = new DropdownView({
                    $host: $( event.currentTarget ),
                    tpl: objectUserDropdownTpl
                });
            });

        }//}}}

    });

    return NavView;
});

