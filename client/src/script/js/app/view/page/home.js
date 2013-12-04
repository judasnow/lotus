define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'v/search',

    'utilities/page',
    'text!tpl/page/home.mustache'

] , function(

    $ ,
    _ ,
    Backbone,
    Mustache,

    SearchView,

    page,

    homePageTpl
) {
    'use strict';

    var HomePageView = Backbone.View.extend({
        id: 'home-page',
        className: 'box',

        template: homePageTpl,

        initialize: function( args ) {
            this._cb = args.cb;

            this.render();
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el , this._cb );

            new SearchView({ $el: this.$el.find( '#search_box' ) });
        }
    });

    return HomePageView;
});

