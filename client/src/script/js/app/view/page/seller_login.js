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
            var that = this;
            this._getUserInput();

            if( _.isEmpty( this._username ) ) {
                this._$errorInfo.text( '用户名不能为空' );
                return;
            }

            if( _.isEmpty( this._password ) ) {
                this._$errorInfo.text( '密码不能为空' );
                return;
            }

            window.e.trigger( 'show_loading' );
            auth.doLogin({
                username: this._username,
                password: this._password
            }, function() {
                window.e.trigger( 'hide_loading' );
                window.e.trigger( 'login_ok' );

                window.routes.navigate( 'main' , {trigger: true});
            }, function() {
                window.e.trigger( 'hide_loading' );

                that._$errorInfo.text( '用户名或密码错误' );
            });
        },//}}}

        render: function() {
        //{{{
            var that = this;

            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el, function() {
                that._getEls();
            });
        }//}}}
    });

    return SellerLoginView;
});


