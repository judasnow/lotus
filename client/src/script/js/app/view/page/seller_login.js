define ([

    "zepto",
    "backbone",
    "mustache",

    "utilities/page",
    "utilities/auth",

    "text!tpl/page/seller_login.mustache"

] , function (

    $ ,
    Backbone ,
    Mustache ,

    page,
    auth,

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
            _.bindAll( this  , "_doLogin" , "_getUserInput" , "render" );

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
            var that = this;

            this._checkInputValues();

            auth.doLogin({

                email: this._email,
                password: this._password

            }, function() {
                //ok
                //window.routes.navigate( "main" , {trigger: true});
            }, function() {
                //fail
                that._$error_info.text( "用户名或密码错误" );
            });
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );

            this._getUserInput();
            this._$error_info = this.$el.find( ".error_info" );

            return this;
        }
    });

    return SellerLoginView;
});


