//@TODO 上传文件的预览功能
define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'q',

    'c/class_a',
    'c/class_b',

    'v/dialog_base',

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

    DialogBaseView,

    Product,

    addNewProductDialogTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl
 ) {
    'use strict';

    //var AddNewProductDialog = Backbone.View.extend({
    var AddNewProductDialog = DialogBaseView.extend({

        initialize: function() {
        //{{{
            this._baseInit();

            this.events = _.extend({
                'change .class_a_option': '_trySetClassBOptions',
                'click .submit': 'do_submit'
            } , this._baseEvents );

            _.bindAll(
                this ,

                'render' ,

                '_get_els',
                'do_submit',
                '_fetchClassAList',
                '_setClassAOptions',
                '_setClassBOptions',
                '_setModel'
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
            this._$originalPrice = this.$el.find( '.original_price' );
            this._$discount = this.$el.find( '.discount' );
            this._$quantity = this.$el.find( '.quantity' );
            this._$class_a = this.$el.find( '.class_a_option' );
            this._$class_b = this.$el.find( '.class_b_option' );
        },//}}}

        _setModel: function() {
        //{{{
            var attrs = {
                name: this._$name.val(),
                describe: this._$describe.val(),
                original_price: this._$originalPrice.val(),
                discount: this._$discount.val(),
                quantity: this._$quantity.val(),
                class_a: this._$class_a.val(),
                class_b: this._$class_b.val()
            };

            this.model.set( attrs );
        },//}}}

        do_submit: function() {
        //{{{
            this._setModel();
            this.model.save();
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

