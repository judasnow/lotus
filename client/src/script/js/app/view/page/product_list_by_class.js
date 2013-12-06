define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'v/product_list',
    'c/product',
    'v/product_list_page',

    'text!tpl/page/product_list_by_class.mustache'

] , function(
    $ ,
    _ ,
    Backbone ,
    Mustache ,

    ProductListView,
    ProductColl,
    ProductListPageView,

    productListByClassPageTpl

) {
    'use strict';

    var ProductListByClass = Backbone.View.extend({

        //@TODO 應該在 url 中提供 class 的名字 而在
        //ajax 請求之中使用相應的 class id 
        //
        //({
        // class_a::string,
        // class_b::string,
        // current_page::number
        //}) => void
        initialize: function( args ) {
            this._classA = args.class_a;
            this._classB = args.class_b;
            this._currentPage = 1;

            if( _.isNumber( args.current_page ) ) {
                this._currentPage = args.current_page;
            }

            _.bindAll( this , 'render' , '_renderProductList' );

        },

        _renderProductList: function() {
             this._productColl = new ProductColl({
                url: config.serverAddress + 'shop_api/products/'
            });

            this._productListView = new ProductListView({
                $el: this.$el.find( '.shop-page-product-list ul' ),
                coll: this._productColl
            });

            //获取并显示分页信息
            this._pageView = new ProductListPageView({
                $el: this.$el.find( '.product-list-pager-list' ),
                getUrl: 'shop_api/product_page_count/',
                currentPage: this._currentPage,
                options: {
                    shop_id: this._model.get( 'shop_id' )
                }
            });

            //获取第一页信息 如果存在的话
            this._productListView.getListByPage( 1 , {
                shop_id: this._model.get( 'shop_id' )
            });
        },

        render: function() {
            
        }
    });

    return ProductListByClass;

});

