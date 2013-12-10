define ([

    'zepto',
    'backbone',
    'mustache',

    'm/user',

    'utilities/page',
    'utilities/auth',
    'utilities/common',

    'text!tpl/page/seller_login.mustache'

] , function (

    $,
    Backbone,
    Mustache,

    User,

    page,
    auth,
    common,

    sellerLoginPageTpl 
) {
    'use strict';

    var SellerLoginView = Backbone.View.extend({

        id: 'seller-login',
        className: 'box',

        template: sellerLoginPageTpl,

        events: {
            'click .submit': '_doLogin'
        },

        initialize: function() {
        //{{{
            _.bindAll(
                this,

                '_doLogin',
                '_getEls',
                '_getUserInput',
                'render'
            );

            this.render();
        },//}}}

        _getUserInput: function() {
        //{{{
            this._username = this._$username.val();
            this._password = this._$password.val();
        },//}}}

        _getEls: function() {
        //{{{
            var _$el = this.$el;

            this._$username = _$el.find( 'input[name="username"]' );
            this._$password = _$el.find( 'input[name="password"]' );
            this._$errorInfo = _$el.find( '.error_info' );
        },//}}}

        _doLogin: function() {
        //{{{
            window.e.trigger( 'show_loading' );

            this._getUserInput();
            var that = this;

            if( _.isEmpty( this._username ) ) {
                window.sysNotice.setMsg( '用户名不能为空' );
                return;
            }

            if( _.isEmpty( this._password ) ) {
                window.sysNotice.setMsg( '密码不能为空' );
                return;
            }

            auth.doLogin({

                username: this._username,
                password: this._password

            }, function() {
                //ok
                window.routes.navigate( 'main' , {trigger: true});
                window.e.trigger( 'hide_loading' );

                window.e.trigger( 'login_ok' );
            }, function() {
                //fail
                that._$error_info.text( '用户名或密码错误' );
                window.e.trigger( 'hide_loading' );
            });
        },//}}}

        render: function() {
        //{{{
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );

            this._getEls();
            this._$error_info = this.$el.find( '.error_info' );
        }//}}}
    });

    return SellerLoginView;
});


