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

        _getEls: function() {
            this._$loading = this.$el.find( ".loading" );
            console.dir( this._$loading )
        },

        initialize: function() {
            _.bindAll( this , "toggleCategoriesBrowse" , "_getEls" , "showLoading" , "hideLoading" );

            this._getEls();
            this._categoriesBrowseView = new CategoriesBrowseView();
        },

        toggleCategoriesBrowse: function() {
            this._categoriesBrowseView.toggle();
        },

        showLoading: function() {
            this._$loading.show();
        },

        hideLoading: function() {
            this._$loading.hide();
        }

    });

    return Nav;
});
