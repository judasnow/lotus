define([
    "underscore",
    "backbone",

    "v/class_a_list"

], function( _ , Backbone , ClassAListView ) {
    "use strict";

    var CategoriesBrowse = Backbone.View.extend({

        id: "categories_browse",
        tagName: "div",

        initialize: function() {
            _.bindAll( this , "render" );

            //初始化主分类列表
            this._classAListView = new ClassAListView( this.$el );

            this.render();
        },

        toggle: function() {
            this.$el.toggle();
        },

        render: function() {
            $( '#wrapper > .box' ).append( this.$el );
        }
    });

    return CategoriesBrowse;
});

