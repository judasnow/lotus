//
// 添加一个新的商品 或者 修改一个已有的商品
// @author <judasnow@gmail.com>
//
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

   'text!tpl/page/edit_product.mustache',
   'text!tpl/dialog/class_a_select.mustache',
   'text!tpl/dialog/class_b_select.mustache',
   'text!tpl/dialog/edit_product_picture_preview_item.mustache'
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

    editProductTpl,
    classASelectOptionTpl,
    classBSelectOptionTpl,
    editProductPicturePreviewItem
//}}}
) {
    'use strict';

    var EditProductPageView = Backbone.View.extend({
        id: 'edit_product',
        className: 'box',

        template: editProductTpl,

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

        initialize: function( args ) {
        //{{{
            var that = this;

            _.bindAll(
                this,

                'render',

                '_getEls',
                '_convertImageUrlToNameOnly',
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
                '_checkAttr',
                'doSubmit'
            );

            this.classAColl = new ClassAColl();
            this.classAColl.on( 'fetch_ok' , this._setClassAOptions );
            this.classBColl = new ClassBColl();
            this.classBColl.on( 'fetch_ok' , this._setClassBOptions );

            // [ file::File | url::string ]
            // 封面图片以及商品细节图片 有可能是
            // 对应于用户选择的图片的文件对象 也 有可能是
            // 之前文件的 url (仅仅在修改信息的时候 会出现这样的情况)
            this._imageFiles = [];
            this._detailImageFiles = [];

            // 用户选择的文件 在上传成功之后 获取的唯一标识 或
            // 者是 url 中对应的 文件名称部分
            // [ string ]
            this._imageName = [];
            this._detailNames = [];

            this._Edit = false;
            if ( typeof args !== 'undefined'
                && typeof args.productId !== 'undefined'
                && ! isNaN( args.productId ) )
            {
                // edit
                //
                // 这里不能先 new 一个 product 再设置 id 因为需要向 initialize 方法传入 id 来
                // 设置不同的 url , 这是前期设计的一个失误
                this._Edit = true;
                this._model = new Product({ id: args.productId });
                this._model.on( 'fetch_ok', this.render );

                this._model.fetch({
                    success: function( model ) {
                        // 初始化商品图片信息
                        var imageUrl = model.get( 'product_image_url' );
                        var detailImageUrl = model.get( 'product_detail_image_url' );

                        if( typeof imageUrl === 'string' ) {
                            that._imageFiles.push( imageUrl );
                            that._imageName.push( that._convertImageUrlToNameOnly( imageUrl ) );
                        }

                        if( $.isArray( detailImageUrl ) ) {
                            _.each(
                                detailImageUrl,
                                function( url ) {
                                    that._detailImageFiles.push( url );
                                    that._detailNames.push( that._convertImageUrlToNameOnly( url ) );
                                }
                            );
                        }

                        model.trigger( 'fetch_ok' );
                    }
                });
            } else {
                // add new
                this._model = new Product();
                this.render();
            }

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

        // 获取 class_a 列表中的信息
        _fetchClassAList: function() {
        //{{{
            this.classAColl.fetch({
                success: function( coll ) {
                    coll.trigger( 'fetch_ok' );
                }
            });
        },//}}}

        _setClassAOptions: function() {
        //{{{
            //渲染 class_a 列表
            this._$classA.html(
                Mustache.to_html(
                    classASelectOptionTpl,
                    {
                        options: this.classAColl.toJSON()
                    }
                )
            );

            //如果是在修改已有商品的信息 则填写相应商品的 class_a 信息
            //并据此加载 class_b 中的信息
            var classAId = this._model.get( 'product_class_a' );
            if( typeof classAId === 'undefined' ) {
                this._trySetClassBOptions( this._$classA.val() );
            } else {
                this._$classA.val( classAId );
                this._trySetClassBOptions( classAId );
            }
        },//}}}

        _setClassBOptions: function() {
        //{{{
            var $classBOption = this.$el.find( '.class_b_option' );
            $classBOption.html( Mustache.to_html( classBSelectOptionTpl , {options: this.classBColl.toJSON()} ) );

            var classBId = this._model.get( 'product_class_b' );
            if( typeof classBId !== 'undefined' ) {
                this._$classB.val( classBId );
            }

        },//}}}

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

        _showFuncBtns: function( event ) {
        //{{{
            $( event.currentTarget )
                .find( '.preview-img-func' ).show();
        },//}}}

        _hideFuncBtns: function( event ) {
        //{{{
            $( event.currentTarget )
                .find( '.preview-img-func' ).hide();
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

        // 返回默认图片 证明数据库中有不一致的情况
        // ( url::string ) => boolean
        _isDefaultImage: function( url ) {
            return url.indexOf( 'defaultimage-product.jpg' ) !== -1;
        },

        //( url::string ) => string
        _convertImageUrlToNameOnly: function( url ) {
        //{{{
            if( typeof url === 'string' ) {
                if( ! this._isDefaultImage( url ) ) {
                    var imageName = url.match( /.{25}\.jpg/i )[0];

                    if( _.isString( imageName ) ) {
                        return imageName.replace( '.jpg', '' );
                    }
                }
            }

            return '';
        },//}}}

        _uploadImages: function( doSubmitCb ) {
        //{{{
            var that = this;
            var targetUrl = "upload_api/do_upload_image/";
            var files = [];

            console.dir( this._imageFiles )
            console.dir( this._detailImageFiles )

            _.each( this._imageFiles, function( file, index ) {
                if ( file instanceof File ) {
                    files.push({
                        type: 'image',
                        file: file
                    })
                }
            });

            _.each( this._detailImageFiles, function( file, index ) {
                if( file instanceof File ) {
                    files.push({
                        type: 'detail_image',
                        file: file
                    });
                }
            });

            if( files.length > 0 ) {

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
                            } else if( type === 'detail_image' ) {
                                that._detailNames.push( imageName );
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
            } else {
                doSubmitCb( null );
            }

        },//}}}

        //@parem where string ['image'|'detail_image']
        _previewImage: function( where ) {
        //{{{
            var that = this;
            var to$El = null;
            var files = [];

            if( where === 'image' ) {

                to$El = this._$imagePreview;
                to$El.html( '' );
                files = this._imageFiles;

            } else if( where === 'detail_image' ) {

                to$El = this._$detailImagePreviewList;
                //@TODO 闪烁的情况
                to$El.html( '' );
                files = this._detailImageFiles;

            }

            _.each( files, function( file, index ) {
                if ( file instanceof File ) {
                    // 从文件
                    var reader = new FileReader();

                    reader.onload = function( event ) {
                        to$El.prepend( Mustache.to_html(
                            editProductPicturePreviewItem,
                            {
                                index: index,
                                src: event.target.result
                            }
                        ));
                    }

                    reader.readAsDataURL( file );
                } else {
                    // 从 url
                    if( that._isDefaultImage( file ) ) {
                        return true;
                    }
                    to$El.prepend( Mustache.to_html(
                        editProductPicturePreviewItem,
                        {
                            index: index,
                            src: file
                        }
                    ));
                }
            });
        },//}}}

        _changeImageInput: function( event ) {
        //{{{
            var $input = $( event.currentTarget );
            var files = $input[0].files;

            if ( $input.hasClass( 'image_input' ) ) {

                //因为之允许有一个 cover image
                this._imageFiles = [];
                this._imageFiles.push( files[0] );
                this._previewImage( 'image' );

            } else if ( $input.hasClass( 'detail_image_input' ) ) {

                this._detailImageFiles =
                    _.filter( _.values( files ), function( item ) {
                        return item instanceof File;
                    }
                ).concat( this._detailImageFiles );

                this._previewImage( 'detail_image' );
            }
        },//}}}

        _removeThisImage: function( event ) {
        //{{{
            var $imageBox = $( event.currentTarget ).parents( '.preview-img-box' );
            var index = $imageBox.attr( 'data-index' );

            if( $imageBox.parents( '.detail-image-preview-list' ).length > 0 ) {
                //detail image
                delete( this._detailImageFiles[index] );
                delete( this._detailNames[index] );
            } else if ( $imageBox.parents( '.image-preview' ).length > 0 ) {
                //cover image
                delete( this._imageFiles[index] );
                delete( this._imageName[index] );
            }

            $imageBox.remove();
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
                    that._model.set( 'image', that._imageName );
                    that._model.set( 'detail_image', that._detailNames.join( ',' ) );

                    if( that._isEdit === false ) {
                        // add new
                        that._model.save( null , {
                            success: function() {
                                window.e.trigger( 'hide_loading' );
                                window.sysNotice.setMsg( '添加新的商品成功' );

                                cb();
                            }
                        });
                    } else {
                        // edit
                        that._model.doUpdate( function() {
                            window.e.trigger( 'hide_loading' );
                            window.sysNotice.setMsg( '更新商品信息成功' );
                            cb();
                        });
                    }
                }
            ]);

        },//}}}

        render: function() {
        //{{{
            this.$el.html(
                Mustache.to_html(
                    this.template,
                    this._model.toJSON()
                )
            );
            this._getEls();

            this._fetchClassAList();

            page.loadPage( this.$el );

            this._previewImage( 'detail_image' );
            this._previewImage( 'image' );
        }//}}}
    });

    return EditProductPageView;
});

