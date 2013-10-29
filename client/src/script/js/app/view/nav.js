define([

    "zepto",
    "backbone",

    "v/categories_browse"

] , function( $ , Backbone , CategoriesBrowseView ) {

    var Nav = Backbone.View.extend({

        el: "#nav",

        events: {
            "click .categories_browse_btn": "toggleCategoriesBrowse"
        },

        initialize: function() {
            _.bindAll( this , "toggleCategoriesBrowse" );

            this._categoriesBrowseView = new CategoriesBrowseView();
        },

        toggleCategoriesBrowse: function() {
            this._categoriesBrowseView.toggle();
        }

    });

    return Nav;
})
