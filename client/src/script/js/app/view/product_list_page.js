define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'config',

    'text!tpl/product_list_pager.mustache'

] , function(
    $,
    _,
    Backbone,
    Mustache,

    config,

    productListPagerTpl

) {
    'use strict';

    //page 似乎没有必要单独使用 coll
    var ProductListPageView = Backbone.View.extend({
        events: {
            'click .product-list-pager-list-ol-item a': '_changeCurrentPage'
        },

        //({
        // getUrl::string,
        // fetchOptions::string,
        // $el::array,
        // currentPage::number,
        // pageNoIndex::number
        //}) => void
        initialize: function( args ) {
        //{{{
            //@TODO param valid
            this._getUrl = args.getUrl;
            this._fetchOptions = args.options;
            this.$el = args.$el;

            if( typeof args.currentPage === 'undefined' || args.currentPage === null || isNaN( args.currentPage ) ) {
                // 页码设置的不合法 默认为 1
                this._currentPage = 1;
            } else {
                this._currentPage = parseInt( args.currentPage );
            }

            // 指定页码出现在 url 中的位置
            if( _.isNumber( args.pageNoIndex ) ) {
                this._pageNoIndex = args.pageNoIndex;
            } else {
                throw new Error( 'pageNoIndex no set' );
            }

            this._MAX_PAGE_ITEM_COUNT = 9;

            _.bindAll(
                this ,

                '_buildPageObj',
                '_changeCurrentPage',
                '_fetchPage',
                'render'
            );

            //目的在于仅仅只是在初始化的时候 获取一次 page
            //并在成功获取信息之后渲染出页面信息
            this._fetchPage();
        },//}}}

        _fetchPage: function() {
        //{{{
            var that = this;

            $.get( config.serverAddress + this._getUrl , this._fetchOptions , function( data ) {
                //如果 page === 0 已经没有继续下去的必要了
                that._page = data[0];

                that.render();
            }, 'json' );
        },//}}}

        // 依据指定页码构造 url
        // 约定 url 最后一部分就是 pageNo
        _changeCurrentPage: function( event ) {
        //{{{
            var pageNo = $( event.currentTarget ).attr( 'data-attr' );
            var hash = window.location.hash.replace( /[\\\/]*$/, '' );
            var hashArray = hash.split( '/' );

            this._currentPage = parseInt( pageNo );

            if ( hashArray[ this._pageNoIndex ] === "" ) {
                // 当前 url 还没有添加 page 信息的情况 直接添加
                window.location.hash = hash + '/' + pageNo;
            } else {
                // 当前 url 已经添加了 page 信息的情况 替换最后一个 solt
                hashArray[ this._pageNoIndex ] = pageNo;
                window.location.hash = hashArray.join( '/' );
            }

            this.render();
        },//}}}

        _buildPageObj: function() {
        //{{{
            var page = this._page;
            if ( page <= 0 ) {
                // 不应该为一个异常
                return;
            }

            var i = 1;
            this._pageObj = [];

            if ( page <= this._MAX_PAGE_ITEM_COUNT ) {
                //总页码数小于最多可以显示的页数 直接显示
                for( var i = 1; i<= page; i++ ) {
                    this._pageObj.push({ page: i });
                }
            } else {
                if( this._currentPage >= this._MAX_PAGE_ITEM_COUNT ) {
                    //@XXX urgly
                    //显示最开始的两页
                    this._pageObj.push({ page: 1 });
                    this._pageObj.push({ page: 2 });
                    this._pageObj.push({ pagePlacehoder: true });

                    //显示当前页码的前三页 加上一个省略
                    this._pageObj.push({ page: this._currentPage - 3 });
                    this._pageObj.push({ page: this._currentPage - 2 });
                    this._pageObj.push({ page: this._currentPage - 1 });
                    this._pageObj.push({ page: this._currentPage });

                    //尝试性的添加当前页码的后三页 如果需要显示的话
                    var currentPagePast3 = this._currentPage + 3;

                    if( currentPagePast3 < page ) {
                        //如果向后延续三页之后仍小于 最大页数 就需要
                        //显示延续的三页之后 再显示最后两页
                        for( i = this._currentPage + 1 ; i<= currentPagePast3 ; i++ ) {
                            this._pageObj.push({ page: i });
                        }
                        this._pageObj.push({ pagePlacehoder: true });
                        this._pageObj.push({ page: page - 1 });
                        this._pageObj.push({ page: page });
                    } else {
                        //延续三页之后将大于当前总的页数 则不再显示最后两页 而
                        //仅仅显示可延续的部分
                        for( i = this._currentPage + 1 ; i<= page ; i++ ) {
                            this._pageObj.push({ page: i });
                        }
                    }

                } else {
                    for( i = 1 ; i<= this._MAX_PAGE_ITEM_COUNT ; i++ ) {
                        this._pageObj.push({ page: i });
                    }
                    this._pageObj.push({ pagePlacehoder: true });
                }
            }

            //prev
            var prevPage = 1;
            var nextPage = page;
            if( this._currentPage - 1 > 1 ) {
                prevPage = this._currentPage - 1;
            }
            if( this._currentPage + 1 < page ) {
                nextPage = this._currentPage + 1;
            }

            this._pageObj.unshift({ prevPage: prevPage });
            this._pageObj.push({ nextPage: nextPage });
        },//}}}

        render: function() {
        //{{{
            this._buildPageObj();

            this.$el.html(
                Mustache.to_html(
                    productListPagerTpl ,
                    {
                        pages: this._pageObj
                    }
                ) 
            );
        }//}}}

    });

    return ProductListPageView;
});

