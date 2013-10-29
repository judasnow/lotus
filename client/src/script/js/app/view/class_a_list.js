define([

    "backbone",
    "mustache",

    "c/class_a",

    "text!tpl/class_a_list_item.mustache"

] , function( Backbone , Mustache , ClassAColl , classAListItemTpl ) {
    "use strict";

    var ClassAListView = Backbone.View.extend({

        className: "categories_list",
        tagName: "div",

        events: {
            "mouseover .row": "_show_class_b",
            "mouseup .row": "_hide_class_b"
        },

        initialize: function( $categoriesBrowse ) {

            this._$categoriesBrowse = $categoriesBrowse;

            _.bindAll( this , "_addAll" , "_addOne" , "_show_class_b" , "_hide_class_b" , "render" );

            this._coll = new ClassAColl();
            this._coll.on( "fetch_ok" , this._addAll );

            this._coll.fetch({
                success: function( coll ) {
                    coll.trigger( "fetch_ok" );
                }
            });

            this.render();
        },

        _show_class_b: function( event ) {
            var $target = $( event.currentTarget );
            var class_a_id = $target.attr( "data-id" );
            console.log( class_a_id );
        },

        _hide_class_b: function( event ) {

        },

        _addAll: function() {
            this._coll.each( this._addOne );
        },

        _addOne: function( item ) {
            this.$el.append( Mustache.to_html( classAListItemTpl , item.toJSON() ) );
        },

        render: function() {
            this._$categoriesBrowse.append( this.$el );
        }

    });

    return ClassAListView;
});
