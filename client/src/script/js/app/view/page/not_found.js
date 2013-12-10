define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'utilities/page',

    'text!tpl/page/page_not_found.mustache'

] , function(
    $ ,
    _ ,
    Backbone ,
    Mustache ,
    page ,
    pageNotFoundTpl 
) {
    'use strict';

    var NotFoundPage = Backbone.View.extend({
        id: 'not-found-page',
        className: 'box',

        template: pageNotFoundTpl,

        initialize: function() {
            this.render();
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );
        }
    });

    return NotFoundPage;
});

