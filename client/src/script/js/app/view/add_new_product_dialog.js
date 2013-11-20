define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'q',

    'c/class_a',
    'c/class_b',

    'm/product',

    'text!tpl/dialog/add_new_product.mustache',
    'text!tpl/dialog/class_a_select.mustache',
    'text!tpl/dialog/class_b_select.mustache'

] , function(
    $ ,
    _,
    Backbone,
    Mustache,
    Q,

    ClassAColl,
    ClassBColl,
    Product,

    addNewProductDialogTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl
 ) {
    'use strict';

    var AddNewProductDialog = Backbone.View.extend({

        events: {
        //{{{
            'change .class_a_option': '_trySetClassBOptions',
            'click .submit': 'do_submit'
        },//}}}

        initialize: function() {
        //{{{
            _.bindAll( 
                this ,

                'render' ,

                '_get_els',
                'do_submit',
                '_fetchClassAList',
                '_setClassAOptions',
                '_setClassBOptions',
                '_get_user_input'
            );

            this.model = new Product();

            this.classAColl = new ClassAColl();
            this.classAColl.on( 'fetch_ok' , this._setClassAOptions );

            this.classBColl = new ClassBColl();
            this.classBColl.on( 'fetch_ok' , this._setClassBOptions );

            this.render();
            this._get_els();
        },//}}}

        _setClassAOptions: function() {
        //{{{
            var $classAOption = this.$el.find( '.class_a_option' );
            $classAOption.html( Mustache.to_html( classASelectOptionTpl , {options: this.classAColl.toJSON()} ) );
        },//}}}

        _trySetClassBOptions: function( e ) {
        //{{{
            var $option = $( e.currentTarget );
            this.classBColl.fetch({
                data: {
                    class_a_id: $option.val()
                },
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });
        },//}}}

        _setClassBOptions: function() {
        //{{{
            var $classBOption = this.$el.find( '.class_b_option' );
            $classBOption.html( Mustache.to_html( classBSelectOptionTpl , {options: this.classBColl.toJSON()} ) );
        },//}}}

        _fetchClassAList: function() {
        //{{{
            this.classAColl.fetch({
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });
        },//}}}

        _get_els: function() {
        //{{{
            this._$name = this.$el.find( '.name' );
            this._$describe = this.$el.find( '.describe' );
            this._$price = this.$el.find( '.price' );
            this._$discount = this.$el.find( '.discount' );
            this._$quantity = this.$el.find( '.quantity' );
            this._$class_a = this.$el.find( '.class_a_option' );
            this._$class_b = this.$el.find( '.class_b_option' );
        },//}}}

        _get_user_input: function() {
        //{{{
            this.model.set( 'name' , this._$name.val() );
        },//}}}

        do_submit: function() {
        //{{{
            this._get_user_input();
        },//}}}

        render: function() {
        //{{{
            this.$el.html( addNewProductDialogTpl );
            this._fetchClassAList();

            $( 'body' ).append( this.$el );
        }//}}}
    });

    return AddNewProductDialog;
});

