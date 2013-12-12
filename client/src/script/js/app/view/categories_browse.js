define([

    'zepto',
    'underscore',
    'backbone',

    'v/class_a_list'

], function( $, _, Backbone, ClassAListView ) {
    'use strict';

    var CategoriesBrowseView = Backbone.View.extend({

        id: 'categories-browse',

        initialize: function() {
            _.bindAll( this , 'render' );

            //初始化主分类列表
            this._classAListView = new ClassAListView( this.$el );

            this.render();
        },

        toggle: function() {
            this.$el.toggle();
        },

        render: function() {
            $( '#categories-browse-box' ).append( this.$el );
        }
    });

    return CategoriesBrowseView;
});

