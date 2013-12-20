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
            var MAX_LENGTH = 35;

            if( typeof args !== 'undefined' && typeof args.id !== 'undefined' && ! isNaN ( args.id ) ) {
                this.set( 'id' , args.id );
                this.url = config.serverAddress + 'product_api/product/?product_id=' + this.get( 'id' );
            } else {
                this.urlRoot = config.serverAddress + 'product_api/new_product/?session_id=' + common.getSessionId();
            }

            this.set({
                'product_describe_summary':
                    helper.cutTextByMaxLength( this.get( 'product_describe' ), MAX_LENGTH ),
                'product_name_summary':
                    helper.cutTextByMaxLength( this.get( 'product_name' ), MAX_LENGTH )
            });

        }
    });

    return Product;
});

