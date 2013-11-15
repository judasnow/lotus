define ([

    "zepto",
    "backbone",
    "mustache",

    "m/user",

    "utilities/page",
    "utilities/auth",
    "utilities/common",

    "text!tpl/page/seller_login.mustache"

] , function (

    $ ,
    Backbone ,
    Mustache ,

    User,

    page,
    auth,
    common,

    sellerLoginPageTpl 
) {
    "use strict";

    var SellerLoginView = Backbone.View.extend({

        id: "seller_login",
        className: "box",
        tagName: "div",

        template: sellerLoginPageTpl,

        events: {
            "click .submit": "_doLogin"
        },

        initialize: function() {
            _.bindAll(
                this,

                "_doLogin",
                "_getEls",
                "_getUserInput",
                "render"
            );

            this.render();
        },

        _getUserInput: function() {
            this._email = this._$email.val();
            this._password = this._$password.val();
        },

        _checkInputValues: function() {
            
        },

        _getEls: function() {
            var _$el = this.$el;

            this._$email = _$el.find( "input[name='email']" );
            this._$password = _$el.find( "input[name='password']" );
        },

        _doLogin: function() {
            window.e.trigger( "show_loading" );

            //this._checkInputValues();

            this._getUserInput();
            var that = this;

            auth.doLogin({

                email: this._email,
                password: this._password

            }, function() {
                //ok
                window.routes.navigate( "main" , {trigger: true});
                window.e.trigger( "hide_loading" );
                window.e.trigger( "login_ok" );

            }, function() {
                //fail
                that._$error_info.text( "用户名或密码错误" );
                window.e.trigger( "hide_loading" );
            });
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );

            this._getEls();
            this._$error_info = this.$el.find( ".error_info" );

            return this;
        }
    });

    return SellerLoginView;
});


