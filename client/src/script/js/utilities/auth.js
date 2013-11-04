define([

    "zepto",

    "config"

] , function( $ , config ) {

    "use strict";

    var auth = {};

    var doLogin = function( email , password ) {
        //@TODO 格式的合法性检查
        $.post( 
            config.serverAddress + "/auth_api/do_login/",
            {
                email: email,
                password: password
            },
            function( res ) {
                console.dir( res );
            }
        );
    };

    auth.doLogin = doLogin;

    return auth;
});
