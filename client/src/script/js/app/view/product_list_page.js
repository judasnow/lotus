define([

    'zepto',
    'backbone',
    'mustache',

    'config',

    'text!tpl/product_list_pager.mustache'

] , function(
    $,
    Backbone,
    Mustache,

    config,

    productListPagerTpl

) {
    'use strict';

    //page 似乎没有必要单独使用 coll
    var ProductListPageView = Backbone.View.extend({
        initialize: function( args ) {
            var getUrl = args.getUrl;
            var fetchOptions = args.options;

            $.get( config.serverAddress + getUrl , fetchOptions , function( data ) {
                var page = JSON.parse( data )[0];
            }, 'josn' );
        },

        render: function() {
            
        }
    });

    return ProductListPageView;
});

