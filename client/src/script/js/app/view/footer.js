define([

    'zepto',
    'underscore',
    'backbone',

    'utilities/auth'

] , function(

    $,
    _,
    Backbone,

    SellerLoginDialogView
) {
    'use strict';

    var FooterView = Backbone.View.extend({

        el: '#footer .box',

        initialize: function() {
            
        }

    });

    return FooterView;
})
