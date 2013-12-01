define([

    'zepto',
    'underscore',
    'backbone'

] , function( $ , _ , Backbone ) {
    'use strict';

    var ProductListView = Backbone.View.extend({
        //({ coll : coll }) => void
        initialize: function( args ) {
            if( typeof args.coll !== 'object' ) {
                throw new Error( 'param invalid' );
            }

            _.bindAll( this , 'render' , '_addAll' , '_addOne' , 'getListByPage' );

            this._page = 1;

            this._coll = args.coll;
            this._coll.on( 'reset' , this._addAll );
        },

        _addOne: function( item ) {
            
        },

        _addAll: function( coll ) {
            
        },

        //( number , object ) -> void
        //其中 option hash 中保存的是 fetch 所需的额外参数
        getListByPage: function( page , options ) {
            if( typeof page !== 'number' ) {
                throw new Error( 'param invalid' );
            }

            var fetchOptions;
            if( typeof options === 'object' ) {
                fetchOptions = _.extend( fetchOptions , {page: this._page} );
            }

            this._coll.fetch({
                data: {
                    page: this._page,
                    shop_id: 1
                },
                reset: true
            });
        }
    });

    return ProductListView;
});

