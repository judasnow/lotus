define([

    'zepto',
    'backbone',

    'config'

] , function( $ , Backbone , config ) {
    'use strict';

    var Product = Backbone.Model.extend({
        defaults: {
            
        },

       // validate: function( attrs ) {

       // },

        initialize: function() {
            //这里明显就是一个设计失误 new_product 是一个 api 而不是一个
            //RESTful 风格的 api 感觉在 bb 中 每一个 model 都必须是和 collect 联系起来的
            //但是遇到新建一个 model 的情况怎么破？ok 看到了 他提供了一个 urlRoot 属性
            this.urlRoot = config.serverAddress + 'product_api/new_product/';
        }
    });

    return Product;
});

