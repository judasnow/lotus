define([
    "underscore",
    "backbone",

    "c/class_a",
    "c/class_b",

    "text!tpl/categories_browse.mustache"

], function( _ , Backbone , ClassAColl , ClassBColl , CategoriesBrowseTpl ) {

    var CategoriesBrowse = Backbone.View.extend({

        el: "#wrapper .box",

        tpl: CategoriesBrowseTpl,

        initialize: function() {
            _.bindAll( this , "render" );

            //@TODO need to cache it?
            this._classAColl = new ClassAColl;
            this.listenTo( this._classAColl , "change" , this.render );

            this._classAColl.fetch();
        },

        toggle: function() {
            
        },

        render: function() {
            this.$el.append( this.tpl );
        }
    });

    return CategoriesBrowse;
});

