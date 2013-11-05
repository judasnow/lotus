define([

    "zepto",

    "config"

] , function( $ , config ) {

    "use strict";

    var auth = {};

    var _loginSuccess = function() {
        
    };

    var _loginFail = function() {
        
    };

    var doLogin = function( email , password ) {
        //@TODO 格式的合法性检查
        $.ajax({
            type: "POST",
            dataType: "json",
            url: config.serverAddress + "/auth_api/do_login/",
            xhrFields: {
                withCredentials: true
            },
            data: {
                email: email,
                password: password,
                session_id: document.cookie
            },
            success: function( res ) {
                //login ok
                var session_id = res.session_id;
                document.cookie = session_id;
            },
            error: function( xhr , type ) {
                //login fail
                console.dir( xhr.status );
            }
        });
    };

    auth.doLogin = doLogin;

    return auth;
});
