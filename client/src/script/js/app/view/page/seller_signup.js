define([

    'zepto',
    'mustache',
    'backbone',

    'utilities/auth',
    'utilities/page',

    'text!tpl/page/seller_signup.mustache'

] , function(

    $,
    Mustache,
    Backbone,

    auth,
    page,

    SellerSignupTpl
 ) {
    'use strict';

    var SellerSignupView = Backbone.View.extend({

        id: 'signup-page',
        className: 'box',

        events: {
            'click .submit': '_doReg'
        },

        template: SellerSignupTpl,

        initialize: function() {
            _.bindAll(
                this,

                '_getEls',
                '_doReg',
                'render'
            );

            //boolean
            this._readyToDoReg = false;

            this.render();
        },

        _getEls: function() {
            this._$usernameInput = this.$el.find( 'input[name="username"]' );
            this._$passwordInput = this.$el.find( 'input[name="password"]' );
            this._$passwordCheckInput = this.$el.find( 'input[name="password_check"]' );
            this._$regCodeInput = this.$el.find( 'input[name="register_code"]' );
            this._$errorInfo = this.$el.find( '.error_info' );

            this._readyToDoReg = true;
        },

        _doReg: function() {
            //开始应该是 disable 状态
            //直到 _getEls 完成之后
            if( ! this._readyToDoReg ) {
                return;
            } else {
                var username = this._$usernameInput.val();
                var password = this._$passwordInput.val();
                var passwordCheck = this._$passwordCheckInput.val();
                var regCode = this._$regCodeInput.val();

                if( _.isEmpty( username ) ) {
                    this._$errorInfo.text( '用户名不能为空' );
                    return;
                }

                if( _.isEmpty( password ) ) {
                    this._$errorInfo.text( '密码不能为空' );
                    return;
                }

                if( password !== passwordCheck ) {
                    this._$errorInfo.text( '两次输入密码不同' );
                    return;
                }

                if( _.isEmpty( regCode ) ) {
                    this._$errorInfo.text( '邀请码不能为空' );
                    return;
                }

                auth.doReg({
                    username: username,
                    password: password,
                    register_code: regCode,

                    user_role: 'saler'
                },
                function( data ) {
                    if( data === 'ok' ) {
                        window.sysNotice.setMsg( '注册成功, 3 秒之后自动, 转到登录页面' );
                        setTimeout( function() {
                            window.router.navigate( '#seller_login' , {trigger: true} );
                        }, 3000 );
                    }
                }
                );
            }
        },

        render: function() {
            var that = this;

            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el , function() {
                that._getEls();
            });
        }
    });

    return SellerSignupView;
});

