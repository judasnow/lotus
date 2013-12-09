define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'utilities/auth',
    'utilities/page',
    'config',

    'text!tpl/page/shop_apply.mustache'

] , function( 
    $,
    _,
    Backbone,
    Mustache,

    auth,
    page,
    config,

    shopApplyPageTpl
) {
    'use strict';

    //店铺申请页面
    var ShopApplyPageView = Backbone.View.extend({
        id: 'shop-apply-page',
        className: 'box',

        events: {
            'click .submit': '_doSubmit'
        },

        template: shopApplyPageTpl,

        initialize: function() {
            _.bindAll( this, '_doSubmit', '_getEls', 'render' );

            this.render();
        },

        _getEls: function() {
            this._$keeperNameInput = this.$el.find( 'input[name="keeper_name"]' );
            this._$keeperTelInput = this.$el.find( 'input[name="keeper_tel"]' );
            this._$shopNameInput = this.$el.find( 'input[name="shop_name"]' );
            this._$shopAddressInput = this.$el.find( 'input[name="shop_address"]' );

            this._$errorInfo = this.$el.find( '.error_info' );
        },

        _doSubmit: function() {
            var keeperName = this._$keeperNameInput.val();
            var keeperTel = this._$keeperTelInput.val();
            var shopName = this._$shopNameInput.val();
            var shopAddress = this._$shopAddressInput.val();

            if ( _.isEmpty( keeperName ) ) {
                this._$errorInfo.text( '申请人名称不能为空' );
                return;
            }

            if( _.isEmpty( keeperTel ) ) {
                this._$errorInfo.text( '申请人联系方式不能为空' );
                return;
            }

            if( _.isEmpty( shopName ) ) {
                this._$errorInfo.text( '店铺名称不能为空' );
                return;
            }

            if( _.isEmpty( shopAddress ) ) {
                this._$errorInfo.text( '店铺地址不能空' );
                return;
            }

            //前两个变名字 是为了和后台吻合
            auth.doApply({
                shopkeeper_name: keeperName,
                shopkeeper_tel: keeperTel,
                shop_name: shopName,
                shop_address: shopAddress
            });
        },

        render: function() {
            var that = this;

            this.$el.html( Mustache.to_html( this.template, '{}' ) );

            page.loadPage( this.$el , function() {
                that._getEls();
            });
        }
    });

    return ShopApplyPageView;
});


