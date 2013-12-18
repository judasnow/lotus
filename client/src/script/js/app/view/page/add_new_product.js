define([
//{{{
   'zepto',
   'underscore',
   'backbone',
   'mustache',
   'async',

   'c/class_a',
   'c/class_b',

   'v/dialog_base',

   'm/product',

   'config',

   'utilities/common',
   'utilities/helper',
   'utilities/page',

   'text!tpl/page/add_new_product.mustache',
   'text!tpl/dialog/class_a_select.mustache',
   'text!tpl/dialog/class_b_select.mustache',
   'text!tpl/dialog/add_new_product_picture_preview_item.mustache'
//}}}
] , function(
//{{{
    $,
    _,
    Backbone,
    Mustache,
    async,

    ClassAColl,
    ClassBColl,

    DialogBaseView,

    Product,

    config,

    common,
    helper,
    page,

    addNewProductTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl,
    addNewProductPicturePreviewItem
//}}}
) {
    'use strict';

    var AddNewProductPageView = Backbone.View.extend({
        id: 'add_new_product',
        className: 'box',

        template: addNewProductTpl,

        events: {
            'change .class_a_option': '_changeClassAOption',

            //cover image
            'change .image_input': '_changeImageInput',
            //detail image
            'change .detail_image_input': '_changeImageInput',

            'mouseover .preview-img-box': '_showFuncBtns',
            'mouseout .preview-img-box': '_hideFuncBtns',
            'click .preview-img-func-remove': '_removeThisImage',
            'click .submit': 'doSubmit'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this ,

                'render' ,

                '_getEls',
                'doSubmit',
                '_changeClassAOption',
                '_fetchClassAList',
                '_setClassAOptions',
                '_setClassBOptions',
                '_setModel',
                '_previewImage',
                '_changeImageInput',
                '_showFuncBtns',
                '_hideFuncBtns',
                '_removeThisImage',
                '_uploadImages',
                '_checkAttr'
            );

            this._model = new Product();

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
            this._trySetClassBOptions( this._$classA.val() );
        },//}}}

        //體現了事件處理同邏輯代碼分開的必要性
        _changeClassAOption: function( event ) {
        //{{{
            var $option = $( event.currentTarget );
            this._trySetClassBOptions( $option.val() )
        },//}}}

        _trySetClassBOptions: function( classAId ) {
        //{{{
            this.classBColl.fetch({
                data: {
                    class_a_id: classAId
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
            this._$name = this.$el.find( 'input[name="name"]' );
            this._$describe = this.$el.find( '.product-describe-textarea' );
            this._$originalPrice = this.$el.find( 'input[name="original_price"]' );
            this._$discount = this.$el.find( 'input[name="discount"]' );
            this._$quantity = this.$el.find( 'input[name="quantity"]' );
            this._$classA = this.$el.find( '.class_a_option' );
            this._$classB = this.$el.find( '.class_b_option' );

            this._$imageInput = this.$el.find( 'input[name="image_input"]' );
            this._$imagePreview = this.$el.find( '.image-preview' );
            this._$detailImageInput = this.$el.find( 'input[name="detail_image_input"]' );
            this._$detailImagePreviewList = this.$el.find( '.detail-image-preview-list' );

            this._$errorInfo = this.$el.find( '.error_info' );
        },//}}}

        //() => boolean
        _checkAttr: function() {
        //{{{
            var attrs = this._model.toJSON();

            if( _.isEmpty( attrs.name ) ) {
                this._$errorInfo.text( '商品名称不能为空' );
                return false;
            }

            if( _.isEmpty( attrs.describe ) ) {
                this._$errorInfo.text( '商品描述不能为空' );
                return false;
            }

            if( _.isEmpty( attrs.discount ) 
                || isNaN( attrs.discount )
                || parseInt( attrs.discount ) > 10
                || parseInt( attrs.discount ) <= 0
            ) {
                this._$errorInfo.text( '折扣信息不能为空，而且必须为一个 0 到 10 之间的数字' );
                return false;
            }

            if( _.isEmpty( attrs.quantity )
                || isNaN( attrs.quantity )
                || attrs.quantity <= 0
                || ( attrs.quantity ).toString().indexOf( '.' ) !== -1
            ) {
                this._$errorInfo.text( '商品数量不能为空，而且必须为一个大于 0 的整数' );
                return false;
            }

            if( _.isEmpty( attrs.class_a ) ) {
                this._$errorInfo.text( '需要选择一个一级分类的值' );
                return false;
            }

            if( _.isEmpty( attrs.class_b ) ) {
                this._$errorInfo.text( '需要选择一个二级分类的值' );
                return false;
            }

            return true;
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

            this._model.set( attrs );
        },//}}}

        _uploadImages: function( doSubmitCb ) {
        //{{{
            var that = this;
            var targetUrl = "upload_api/do_upload_image/";

            var files = [];

            _.each( this._imageFiles, function( file, index ) {
               files.push({
                   type: 'image',
                   file: file
               });
            });

            _.each( this._detailImageFiles, function( file, index ) {
               files.push({
                   type: 'detail_image',
                   file: file
               });
            });

            window.sysNotice.setMsg( '上传图片中...' );

            async.eachSeries(

                files,

                function( fileInfoObj, cb ) {
                    var type = fileInfoObj.type;
                    var file = fileInfoObj.file;

                    common.uploadFile( file, targetUrl, function( resText ) {
                        var resObj = JSON.parse( resText );
                        var imageName = resObj.image_name;

                        //@XXX
                        if( type === 'image' ) {
                            that._imageName = imageName;
                            that._model.set( 'image' , that._imageName );
                        } else if( type === 'detail_image' ) {
                            that._detailNames.push( imageName );
                            that._model.set( 'detail_image' , that._detailNames.join( ',' ) );
                        }

                        cb( null );
                    });

                },

                function( err ) {

                    if( err ) {
                        window.sysNotice.setMsg( '上传图片时发生错误，请稍后再试一次' );
                    }

                    doSubmitCb();
                }
            );

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

        _changeImageInput: function( event ) {
        //{{{
            var $input = $( event.currentTarget );
            var files = $input[0].files;

            if( $input.hasClass( 'image_input' ) ) {

                this._imageFiles = files;
                this._previewImage( 'image' );

            } else if( $input.hasClass( 'detail_image_input' ) ) {

                _.extend( this._detailImageFiles , files );
                this._previewImage( 'detail_image' );

            } else {
                //shoud never be here
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

            if( ! this._checkAttr() ) {
                window.sysNotice.setMsg( '填写信息无效，请修改表单的内容' );
                //return;
            }

            window.e.trigger( 'show_loading' );

            // 提交的时候 要求先提交图片
            // 因为需要服务器返回 图片的名称
            async.series([

                function( cb ) {
                    that._uploadImages( cb );
                },

                function( cb ) {
                    that._model.save( null , {
                        success: function() {
                            window.e.trigger( 'hide_loading' );
                            window.sysNotice.setMsg( '添加新的商品成功' );
                            cb();
                        }
                    });
                }

            ]);

        },//}}}

        render: function() {
        //{{{
            this.$el.html( Mustache.to_html( this.template ) );
            this._fetchClassAList();
            page.loadPage( this.$el );
        }//}}}
    });

    return AddNewProductPageView;
});

