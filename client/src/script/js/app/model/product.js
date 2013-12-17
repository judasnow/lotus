define([

    'zepto',
    'backbone',

    'config',

    'utilities/common',
    'utilities/helper'

] , function( $ , Backbone , config , common , helper ) {
    'use strict';

    var Product = Backbone.Model.extend({
        initialize: function( args ) {
            var PRODUCT_DESCRIBE_MAX_LENGTH = 35;

            if( typeof args !== 'undefined' && typeof args.id !== 'undefined' && ! isNaN ( args.id ) ) {
                this.set( 'id' , args.id );
                this.url = config.serverAddress + 'product_api/product/?product_id=' + this.get( 'id' );
            } else {
                this.urlRoot = config.serverAddress + 'product_api/new_product/?session_id=' + common.getSessionId();
            }

            (function( desc , that ){
                if( typeof desc !== 'undefined' ) {
                    if( desc.length > PRODUCT_DESCRIBE_MAX_LENGTH ) {
                        that.set(
                            'product_describe_sumary',
                            helper.chineseSubStr( desc , 0 , PRODUCT_DESCRIBE_MAX_LENGTH ) + ' ...'
                        );
                    } else {
                        that.set( 'product_describe_sumary' , desc );
                    }
                }
            })( this.get( 'product_describe' ) , this );

        }
    });

    return Product;
});

