define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'text!tpl/page/shop_page_product_list_item.mustache'

] , function (
    $ ,
    _ ,
    Backbone,
    Mustache,

    shopPageProductListItemTpl
) {
    'use strict';

    var ProductListView = Backbone.View.extend({
        events: {
            'click .shop-page-product-list-item': '_goProductDetailPage'
        },

        //({ coll : coll }) => void
        initialize: function( args ) {
        //{{{
            if( typeof args.coll !== 'object' ) {
                throw new Error( 'param invalid' );
            }

            this.$el = args.$el;

            _.bindAll(
                this ,

                'render' ,
                '_addAll' ,
                '_addOne' ,
                '_goProductDetailPage',
                'getListByPage'
            );

            this._coll = args.coll;
            this._coll.on( 'fetch_ok' , this._addAll );
        },//}}}

        //@TODO 延时效果
        _addOne: function( item ) {
        //{{{
            this.$el.append( Mustache.to_html( shopPageProductListItemTpl , item.toJSON() ) );
        },//}}}

        _addAll: function() {
        //{{{
            this._coll.each( this._addOne );
        },//}}}

        //( number , object ) -> void
        //其中 option hash 中保存的是 fetch 所需的额外参数
        getListByPage: function( page , options ) {
        //{{{
            if( typeof page !== 'number' ) {
                throw new Error( 'param invalid' );
            }

            var fetchOptions = {};
            if( typeof options === 'object' ) {
                fetchOptions = _.extend( options , {page: page} );
            }

            this._coll.fetch({
                data: fetchOptions,
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });
        },//}}}

        _goProductDetailPage: function( e ) {
        //{{{
            var productId = $( e.currentTarget ).attr( 'data-attr' );
            window.routes.navigate( '/product_detail/' + productId , {trigger: true} );
        }//}}}

    });

    return ProductListView;
});

