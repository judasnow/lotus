//page 
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
            var _$el = this.$el;

            this._$email = _$el.find( "input[name='email']" );
            this._$password = _$el.find( "input[name='password']" );

            this._email = this._$email.val();
            this._password = this._$password.val();
        },

        _doLogin: function() {
            this._getUserInput();

            auth.doLogin({

                email: this._email,
                password: this._password

            }, function() {
                //ok
                //window.routes.navigate( "main" , {trigger: true});
                console.dir( "ok" );
            }, function() {
                //fail
                console.log( "login fail" );
            });
        },

        render: function() {
            this.$el.html( Mustache.to_html( this.template ) );
            page.loadPage( this.$el );
            return this;
        }
    });

    return SellerLoginView;
});


