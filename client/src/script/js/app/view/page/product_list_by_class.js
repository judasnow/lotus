define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'v/product_list',
    'c/product',
    'v/product_list_page',

    'utilities/page',
    'config',

    'text!tpl/page/product_list_by_class.mustache'

] , function(
    $ ,
    _ ,
    Backbone ,
    Mustache ,

    ProductListView,
    ProductColl,
    ProductListPageView,

    page,
    config,

    productListByClassPageTpl

) {
    'use strict';

    var ProductListByClass = Backbone.View.extend({

        id: 'product-list-by-class-page',
        className: 'box',

        template: productListByClassPageTpl,

        //@TODO 應該在 url 中提供 class 的名字 而在
        //ajax 請求之中使用相應的 class id 
        //
        //({
        // class_a::string,
        // class_b::string,
        // current_page::number
        //}) => void
        initialize: function( args ) {
        //{{{
            this._classA = args.class_a;
            this._classB = args.class_b;

            if( typeof args.currentPage === 'undefined' || args.currentPage === null || isNaN( args.currentPage ) ) {
                // 页码设置的不合法 默认为 1
                this._currentPage = 1;
            } else {
                this._currentPage = parseInt( args.currentPage );
            }

            _.bindAll( this , 'render' , '_renderProductList' );

            this.render();
        },//}}}

        _renderProductList: function() {
        //{{{
            this._productColl = new ProductColl({
                url: config.serverAddress + 'home_api/category_products/'
            });

            this._productListView = new ProductListView({
                $el: this.$el.find( '.shop-page-product-list ul' ),
                coll: this._productColl
            });

            //获取并显示分页信息
            this._pageView = new ProductListPageView({
                $el: this.$el.find( '.product-list-pager-list' ),
                getUrl: 'home_api/category_products_page/',
                currentPage: this._currentPage,
                pageNoIndex: 3,
                options: {
                    class_a: this._classA,
                    class_b: this._classB
                }
            });

            //获取第一页信息 如果存在的话
            this._productListView.getListByPage( this._currentPage, {
                class_a: this._classA,
                class_b: this._classB
            });
        },//}}}

        render: function() {
        //{{{
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );

            this._renderProductList();
        }//}}}
    });

    return ProductListByClass;
});

