define([

    'zepto',
    'backbone',

    'config',

    'utilities/common'

] , function( $ , Backbone , config , common ) {
    'use strict';

    var Product = Backbone.Model.extend({
        initialize: function( args ) {
            if( typeof args.id !== 'undefined' && ! isNaN ( args.id ) ) {
                this.set( 'id' , args.id );
                this.url = config.serverAddress + 'product_api/product/?product_id=' + this.get( 'id' );
            } else {
                this.urlRoot = config.serverAddress + 'product_api/new_product/?session_id=' + common.getSessionId();
            }
        }
    });

    return Product;
});

