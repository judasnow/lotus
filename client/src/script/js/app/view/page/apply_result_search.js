define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'utilities/page',

    'text!tpl/page/apply_result_search.mustache'

] , function( $, _, Backbone, Mustache, page, ApplyResultSearchTpl ) {
    'use strict';

    var ApplyResultSearchPageView = Backbone.View.extend({
        id: 'apply-result-search-page',
        className: 'box',

        template: ApplyResultSearchTpl,

        initialize: function() {
            _.bindAll( this, 'render', '_getEls', '_doSearch' );
            this.render();
        },

        _getEls: function() {

        },

        _doSearch: function() {
            
        },

        render: function() {
            var that = this;

            this.$el.html( Mustache.to_html( this.template, '{}' ) );

            page.loadPage( this.$el, function() {
                that._getEls();
            });
        }
    });

    return ApplyResultSearchPageView;
});


