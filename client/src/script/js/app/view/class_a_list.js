define([

    'backbone',
    'mustache',

    'c/class_a',

    'v/class_b_list',

    'text!tpl/class_a_list_item.mustache'

] , function(
    Backbone,
    Mustache,

    ClassAColl,
    ClassBListView,

    classAListItemTpl
) {
    'use strict';

    var ClassAListView = Backbone.View.extend({

        className: 'categories-list',

        events: {
            'mouseover .row': '_show_class_b',
            'mouseleave .row': '_mouseleave_row' 
        },

        initialize: function( $categoriesBrowse ) {
        //{{{
            this._$categoriesBrowse = $categoriesBrowse;

            _.bindAll(
                this ,

                '_addAll',
                '_addOne',
                '_show_class_b',
                '_mouseleave_row',
                'render'
            );

            //@TODO 1 cache it ( store in localStore or window ? )
            //      2 嘗試性的使用 web worker
            this._coll = new ClassAColl();
            this._coll.on( 'fetch_ok' , this._addAll );

            this._coll.fetch({
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });

            this.render();
        },//}}}

        //在 bubbling 阶段捕获该事件 获取当前元素
        _show_class_b: function( e ) {
        //{{{
            var $row = $( e.currentTarget );
            var classAId = $row.attr( 'data-id' );
            var classAContent = $row.find( '.text' ).text();

            $row.find( '.icon' ).show();
            this._classBListView = new ClassBListView(
                this._$categoriesBrowse,
                classAId,
                classAContent
            );
        },//}}}

        _mouseleave_row: function( e ) {
        //{{{
            var $currItem = $( e.currentTarget );

            $currItem.find( '.icon' ).hide();
        },//}}}

        _addAll: function() {
        //{{{
            this._coll.each( this._addOne );
        },//}}}

        _addOne: function( item ) {
        //{{{
            this.$el.append( Mustache.to_html( classAListItemTpl , item.toJSON() ) );
        },//}}}

        render: function() {
        //{{{
            this._$categoriesBrowse.append( this.$el );
        }//}}}

    });

    return ClassAListView;
});
