define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'utilities/common',

    'text!tpl/page/shop_page_product_list_item.mustache',
    'text!tpl/product_list_empty.mustache'

] , function (
    $ ,
    _ ,
    Backbone,
    Mustache,

    common,

    shopPageProductListItemTpl,
    productListEmptyTpl
) {
    'use strict';

    var ProductListView = Backbone.View.extend({
        events: {
            'mouseover .shop-page-product-list-item': '_showToolBar',
            'mouseleave .shop-page-product-list-item': '_hideToolBar',
            'click .product-list-item-toolbar .edit-btn': '_editProduct',
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

                '_editProduct',
                '_showToolBar',
                '_showToolBar',
                '_addAll',
                '_addOne',
                '_goProductDetailPage',
                'getListByPage',
                'render'
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

        _showToolBar: function( event ) {
        //{{{
            var $item = $( event.currentTarget );
            $item.find( '.product-list-item-toolbar' ).show();
        },//}}}

        _hideToolBar: function( event ) {
        //{{{
            var $item = $( event.currentTarget );
            $item.find( '.product-list-item-toolbar' ).hide();
        },//}}}

        _editProduct: function( event ) {
        //{{{
            var $item = $( event.currentTarget ).parents( '.shop-page-product-list-item' ),
                productId = $item.attr( 'data-attr' );

            window.routes.navigate( '/edit_product/' + productId, {trigger: true} );
        },//}}}

        //( number , object ) -> void
        //其中 option hash 中保存的是 fetch 所需的额外参数
        getListByPage: function( page, options ) {
        //{{{
            var that = this;

            if( isNaN( page ) ) {
                throw new Error( 'param invalid' );
            }

            var fetchOptions = {};
            if( typeof options === 'object' ) {
                fetchOptions = _.extend( options , {page: page, session_id: common.getSessionId() } );
            }

            this._coll.fetch({
                data: fetchOptions,
                success: function( coll ) {
                    if( coll.length > 0 ) {
                        coll.trigger( 'fetch_ok' );
                    } else {
                        if( page !== 1 ) {
                            window.sysNotice.setMsg( '没有商品可以显示了' );
                        } else {
                            window.sysNotice.setMsg( '没有商品可以显示' );
                        }
                    }
                },
                error: function( coll, xhr ) {
                    //返回结果为空
                    if ( xhr.status === 404 ) {
                        that.$el.append( productListEmptyTpl );
                    }
                }
            });
        },//}}}

        _goProductDetailPage: function( event ) {
        //{{{
            var $target = $( event.target );

            if( ! $target.is( '.edit-btn' ) && ! $target.is( '.product-list-item-toolbar' ) ) {
                var productId = $( event.currentTarget ).attr( 'data-attr' );
                window.routes.navigate( '/product_detail/' + productId , {trigger: true} );
            }
        }//}}}

    });

    return ProductListView;
});

