define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'c/product',
    'v/product_list',
    'v/product_list_page',

    'utilities/page',
    'config',

    'text!tpl/page/search_result.mustache'

] , function(
    $ ,
    _ ,
    Backbone ,
    Mustache ,

    ProductColl,
    ProductListView,
    ProductListPageView,

    page,
    config,

    searchResultPageTpl
) {
    'use strict';

    var SearchResultPage = Backbone.View.extend({

        id: 'search-result-page',
        className: 'box',
        template: searchResultPageTpl,

        // ({
        //  q::string,
        //  current_page::number
        // })
        //@XXX 注意注入问题
        initialize: function( args ) {
            this._q = '';
            this._currentPage = 1;
            if( typeof args.q !== 'undefined' && _.isString( args.q ) ) {
                this._q = encodeURI( args.q );
            }
            if( typeof args.current_page !== 'undefined' && _.isNumber( args.current_page ) ) {
                this._currentPage = args.current_page;
            }

            _.bindAll( this , '_renderProductList' , 'render' );

            this.render();
        },

        _renderProductList: function() {
            this._productColl = new ProductColl({
                url: config.serverAddress + 'home_api/search/'
            });
            this._productListView = new ProductListView({
                $el: this.$el.find( '.shop-page-product-list ul' ),
                coll: this._productColl
            });

            //获取并显示分页信息
            this._pageView = new ProductListPageView({
                $el: this.$el.find( '.product-list-pager-list' ),
                getUrl: 'home_api/search_result_page/',
                currentPage: this._currentPage,
                options: {
                    search_string: this._q
                }
            });

            //获取第一页信息 如果存在的话
            this._productListView.getListByPage( this._currentPage , {
                search_string: this._q
            });

        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );

            this._renderProductList();

            return this;
        }
    });

    return SearchResultPage;
});

