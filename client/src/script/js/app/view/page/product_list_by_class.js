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

            
        },

        render: function() {
            
        }
    });

    return ProductListByClass;

});

