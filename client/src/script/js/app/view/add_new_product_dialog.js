define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'q',
    'async',

    'c/class_a',
    'c/class_b',

    'v/dialog_base',

    'm/product',

    'config',

    'utilities/common',

    'text!tpl/dialog/add_new_product.mustache',
    'text!tpl/dialog/class_a_select.mustache',
    'text!tpl/dialog/class_b_select.mustache',
    'text!tpl/dialog/add_new_product_picture_preview_item.mustache'

] , function(
    $ ,
    _,
    Backbone,
    Mustache,
    Q,
    async,

    ClassAColl,
    ClassBColl,

    DialogBaseView,

    Product,

    config,

    common,

    addNewProductDialogTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl,
    addNewProductPicturePreviewItem
 ) {
    'use strict';

    var AddNewProductDialog = DialogBaseView.extend({
        id: 'add_new_product_dialog',
        className: 'dialog_box',

        initialize: function() {
        //{{{
            this._baseInit();

            this.events = _.extend({

                'change .class_a_option': '_trySetClassBOptions',
                'change .image_input': '_changeImageInput',
                'change .detail_image_input': '_changeImageInput',
                'mouseover .preview-img-box': '_showFuncBtns',
                'mouseout .preview-img-box': '_hideFuncBtns',
                'click .preview-img-func-remove': '_removeThisImage',
                'click .submit': 'doSubmit'

            } , this._baseEvents );

            _.bindAll(
                this ,

                'render' ,

                '_getEls',
                'doSubmit',
                '_fetchClassAList',
                '_setClassAOptions',
                '_setClassBOptions',
                '_setModel',
                '_previewImage',
                '_changeImageInput',
                '_showFuncBtns',
                '_hideFuncBtns',
                '_removeThisImage',
                '_uploadImages'
            );

            this.model = new Product();

            this.classAColl = new ClassAColl();
            this.classAColl.on( 'fetch_ok' , this._setClassAOptions );

            this.classBColl = new ClassBColl();
            this.classBColl.on( 'fetch_ok' , this._setClassBOptions );

            this.render();
            this._getEls();

            //size = 1
            this._imageFiles = {};
            //size >= 1
            this._detailImageFiles = {};

            this._imageName = '';
            this._detailNames = [];
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

        _showFuncBtns: function( e ) {
        //{{{
            $( e.currentTarget )
                .find( '.preview-img-func' )
                .css( 'z-index' , '999' );
        },//}}}

        _hideFuncBtns: function( e ) {
        //{{{
            $( e.currentTarget )
                .find( '.preview-img-func' )
                .css( 'z-index' , '997' );
        },//}}}

        _getEls: function() {
        //{{{
            //@TODO 修改 class name
            this._$name = this.$el.find( '.name' );
            this._$describe = this.$el.find( '.describe' );
            this._$originalPrice = this.$el.find( '.original_price' );
            this._$discount = this.$el.find( '.discount' );
            this._$quantity = this.$el.find( '.quantity' );
            this._$classA = this.$el.find( '.class_a_option' );
            this._$classB = this.$el.find( '.class_b_option' );

            this._$imageInput = this.$el.find( '.image_input' );
            this._$imagePreview = this.$el.find( '.image-preview' );
            this._$detailImageInput = this.$el.find( '.detail_image_input' );
            this._$detailImagePreviewList = this.$el.find( '.detail-image-preview-list' );
        },//}}}

        _setModel: function() {
        //{{{
            var attrs = {
                name: this._$name.val(),
                describe: this._$describe.val(),
                original_price: this._$originalPrice.val(),
                discount: this._$discount.val(),
                quantity: this._$quantity.val(),
                class_a: this._$classA.val(),
                class_b: this._$classB.val()
            };

            this.model.set( attrs );
        },//}}}

        _uploadImages: function( cb ) {
        //{{{
            var that = this;
            var targetUrl = "upload_api/do_upload_image/";

            var fileLists = {
                image: this._imageFiles,
                detail_image: this._detailImageFiles
            };

            _.each( fileLists , function( files , type ) {

                async.each(

                    files ,

                    function( file , cb ) {
                        common.uploadFile( file , targetUrl , function( resText ) {
                            var resObj = JSON.parse( resText );
                            var imageName = resObj.image_name;

                            if( type === 'image' ) {
                                that._imageName = imageName;
                            } else if( type === 'detail_image' ) {
                                that._detailNames.push( imageName );
                            }

                            cb( null );

                        });
                    },

                    function( err ) {
                        if( err !== null ) {
                            console.dir( 'err' + err );
                        } else {

                            if( type === 'image' ) {
                                that.model.set( 'image' , that._imageName );
                            } else {
                                that.model.set( 'detail_image' , that._detailNames.join( ',' ) );
                            }

                            cb();
                        }
                    }

                );
            });
        },//}}}

        //@parem where string ['image'|'detail_image']
        _previewImage: function( where ) {
        //{{{
            var to$El = null;
            var files = [];

            if( where === 'image' ) {
                to$El = this._$imagePreview;
                to$El.html( '' );
                files = this._imageFiles;
            } else if( where === 'detail_image' ) {
                to$El = this._$detailImagePreviewList;
                files = this._detailImageFiles;
            } else {
                //shoud not be here
            }

            _.each( files , function( file , index ) {
                var reader = new FileReader();

                reader.onload = function( e ) {
                    to$El.prepend( Mustache.to_html(
                        addNewProductPicturePreviewItem,
                        {
                            index: index,
                            src: e.target.result 
                        }
                    ));
                }

                reader.readAsDataURL( file );
            });
        },//}}}

        //@parem e event object
        _changeImageInput: function( e ) {
        //{{{
            var $input = $( e.currentTarget );
            var files = $input[0].files;

            if( $input.hasClass( 'image_input' ) ) {
                this._imageFiles = files;
                this._previewImage( 'image' );
            } else if( $input.hasClass( 'detail_image_input' ) ) {
                _.extend( this._detailImageFiles , files );
                this._previewImage( 'detail_image' );
            } else {
                //shoud not be here
            }
        },//}}}

        _removeThisImage: function( e ) {
        //{{{
            $( e.currentTarget )
                .parent()
                .parent()
                .remove();
        },//}}}

        doSubmit: function() {
        //{{{
            var that = this;
            this._setModel();
            window.e.trigger( 'show_loading' );

            async.series([

                function( cb ) {
                    that._uploadImages( cb );
                },

                function( cb ) {
                    that.model.save( null , {
                        success: function() {
                            console.log( 'save ok' )
                            window.e.trigger( 'hide_loading' );
                            cb();
                            that.$el.hide();
                        }
                    });
                }

            ]);

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

