define([

    'zepto',
    'backbone',
    'mustache',

    'text!tpl/hot_list.mustache'

] , function(

    $ ,
    Backbone,
    Mustache,

    HotListTpl

) {
    'use strict';

    var HotListBasView = Backbone.View.extend({

        className:  'items_list',

        //@param id string 当前 list DOM 元素的 id
        //@param coll object 当前 list 显示的 bb coll
        //@param itemTpl string 
        _baseInit: function( id, coll, itemTpl ) {
            if( typeof id !== 'string' 
                || typeof coll !== 'object'
                || typeof itemTpl !== 'string' )
            {
                throw new Error( 'extend HotListBasView param invalid' );
            }

            this.$el = $( '#' + id );
            this._id = id;
            this._coll = coll;
            this._itemTpl = itemTpl;

            _.bindAll(
                this,

                '_render',
                '_addAll',
                '_addOne'
            );

            this.listenTo( this._coll , 'fetch_ok' , this._addAll );

            this._coll.fetch({
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });

            this._render();
        },

        _addAll: function() {
            this._coll.each( this._addOne );
        },

        _addOne: function( item ) {
            //@TODO cache it
            this.$el
                .find( '.row' )
                .append( Mustache.to_html( this._itemTpl , item.toJSON() ) );
        },

        _render: function() {
            switch( this._id ) {
                case 'hot_product_recommend':
                    this._listTitle = '热门商品推荐';
                    break;
                case 'hot_shop_recommend':
                    this._listTitle = '热门店铺推荐';
                    break;
            }
            this.$el
                .html( Mustache.to_html( HotListTpl , { listTitle: this._listTitle } ) );
        }
    });

    return HotListBasView;
});


