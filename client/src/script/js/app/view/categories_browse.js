define([
    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'c/class_a',
    'c/class_b',

    'text!tpl/categories_browse.mustache',
    'text!tpl/class_a_list_item.mustache',
    'text!tpl/class_b_list_item.mustache'

], function(
    $,
    _,
    Backbone,
    Mustache,

    ClassAColl,
    ClassBColl,

    categoriesBrowseTpl,
    classAListItemTpl,
    classBListItemTpl
) {
    'use strict';

    var CategoriesBrowseView = Backbone.View.extend({

        el: '#categories-browse-box',
        template: categoriesBrowseTpl,

        events: {
            'mouseleave': '_hideCategoriesDetail',

            //class_a 
            'mouseover .categories-list .row': '_mouseoverCategoriesListRow',
            'mouseleave .categories-list .row': '_mouseleaveCategoriesListRow',

            //class_b
            'click .categories-detail .categories-detail-row': '_goProductListByClassPage'
        },

        initialize: function() {
            _.bindAll(
                this,

                '_getEls',
                '_renderClassAlist',
                '_renderClassBlist',
                '_hideCategoriesDetail',
                '_goProductListByClassPage',
                '_render'
            );

            this._render();

            this._classAColl = new ClassAColl();
            this._classBColl = new ClassBColl();

            this.listenTo( this._classAColl, 'reset', this._renderClassAlist );
            this.listenTo( this._classBColl, 'reset', this._renderClassBlist );

            this._classAColl.fetch({reset: true});
        },

        toggle: function() {
            this.$el.toggle();
        },

        //需要在 render 之後調用
        _getEls: function() {
            this._$categoriesList = this.$el.find( '.categories-list' );
            this._$categoriesDetail = this.$el.find( '.categories-detail' );
        },

        //class_a
        //{{{
        _mouseleave_row: function( event ) {
            var $currItem = $( event.currentTarget );

            $currItem.find( '.icon' ).hide();
        },

        _mouseoverCategoriesListRow: function( event ) {
            var $row = $( event.currentTarget );
            var classAId = $row.attr( 'data-id' );
            this._classAId = classAId;
            var classAContent = $row.find( '.text' ).text();

            this._classBColl.fetch({
                data: {
                    class_a_id: classAId
                },
                reset: true
            });
        },

        _renderClassAlist: function() {
            var $categoriesList = this._$categoriesList;

            this._classAColl.forEach( function( item ) {
                $categoriesList.append(
                    Mustache.to_html(
                        classAListItemTpl,
                        item.toJSON()
                    )
                );
            });
        },
        //}}}

        //class_b
        //{{{
        _renderClassBlist: function() {
            var $categoriesDetail = this._$categoriesDetail;

            $categoriesDetail
                .html( '' );

            this._classBColl.forEach( function( item ) {
                $categoriesDetail.append(
                    Mustache.to_html(
                        classBListItemTpl,
                        item.toJSON()
                    )
                );
            });
        },

        _goProductListByClassPage: function( event ) {
            var $row = $( event.currentTarget );
            var classBId = $row.attr( 'data-id' );

            window.routes.navigate(
                '/product_list_by_class/' + this._classAId + '/' + classBId,
                {
                    trigger: true
                }
            );
        },

        _hideCategoriesDetail: function() {
            this._$categoriesDetail.html( '' );
        },
        //}}}

        _render: function() {
            this.$el.html( this.template );
            this._getEls();
        }
    });

    return CategoriesBrowseView;
});

