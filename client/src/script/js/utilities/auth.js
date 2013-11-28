define([

    'zepto',
    'config'

] , function( $ , config ) {
    'use strict';

    var doLogin = function( info , success , fail ) {
    //{{{
        if( arguments.length !== 3 ||
            typeof info.username === 'undefined' ||
            typeof info.password === 'undefined' ||
            typeof success !== 'function' ||
            typeof fail !== 'function'
        ) {
            throw new Error( 'params invalid' + arguments );
        } else {
            var username = info.username;
            var password = info.password;

            var data = {
                username: username,
                password: password
            };

            var session_id = window.sessionStorage.getItem( 'session_id' );
            if( session_id !== null ) {
                data.session_id = session_id;
            }

            //@TODO 格式的合法性检查
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: config.serverAddress + '/auth_api/do_login/',
                data: data,

                success: function( res ) {
                    //login ok
                    //返回当前 session_id
                    var session_id = res.session_id;
                    window.sessionStorage.setItem( 'session_id' , session_id );

                    success();
                },

                error: function( xhr , type ) {
                    //login fail
                    if( xhr.status !== 400 ) {
                        //登录失败期待的返回值是 400
                        console.dir( 'login fail but status code isn`t 400 but ' + xhr.status );
                    }

                    fail();
                }
            });
        }
    };//}}}

    //@XXX 退出登录失败的情况是否需要同时考虑 ?
    // 先不考虑
    var doLogout = function( success ) {
    //{{{
        window.sessionStorage.clear();

        if( typeof success === 'function' ) {
            success();
        }
    };//}}}

    var auth = {
        doLogin: doLogin,
        doLogout: doLogout
    };

    return auth;
});

