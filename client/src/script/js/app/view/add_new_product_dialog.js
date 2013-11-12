define([

    "zepto",
    "underscore",
    "backbone",
    "mustache",
    "q",

    "c/class_a",
    "c/class_b",

    "text!tpl/dialog/add_new_product.mustache",
    "text!tpl/dialog/class_a_select.mustache",
    "text!tpl/dialog/class_b_select.mustache"

] , function(
    $ ,
    _,
    Backbone,
    Mustache,
    Q,

    ClassAColl,
    ClassBColl,

    addNewProductDialogTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl
 ) {
    "use strict";

    var AddNewProductDialog = Backbone.View.extend({

        events: {
            "change .class_a_option": "_trySetClassBOptions"
        },

        initialize: function() {
            _.bindAll( 
                this ,
                "render" ,
                "_fetchClassAList",
                "_setClassAOptions",
                "_setClassBOptions"
            );

            this.classAColl = new ClassAColl();
            this.classAColl.on( "fetch_ok" , this._setClassAOptions );

            this.classBColl = new ClassBColl();
            this.classBColl.on( "fetch_ok" , this._setClassBOptions );

            this.render();
        },

        _setClassAOptions: function() {
            var $classAOption = this.$el.find( ".class_a_option" );
            $classAOption.html( Mustache.to_html( classASelectOptionTpl , {options: this.classAColl.toJSON()} ) );
        },

        _trySetClassBOptions: function( e ) {
            var $option = $( e.currentTarget );
            this.classBColl.fetch({
                data: {
                    class_a_id: $option.val()
                },
                success: function( coll ) {
                    coll.trigger( "fetch_ok" );
                }
            });
        },

        _setClassBOptions: function() {
            var $classBOption = this.$el.find( ".class_b_option" );
            $classBOption.html( Mustache.to_html( classBSelectOptionTpl , {options: this.classBColl.toJSON()} ) );
        },

        _fetchClassAList: function() {
            this.classAColl.fetch({
                success: function( coll ) {
                    coll.trigger( "fetch_ok" );
                }
            });
        },

        render: function() {
            this.$el.html( addNewProductDialogTpl );
            this._fetchClassAList();

            $( "body" ).append( this.$el );
        }

    });

    return AddNewProductDialog;
});

