define([

    'zepto',
    'underscore',
    'config'

] , function( $, _, config ) {
    'use strict';

    var doLogin = function( info, success, fail ) {
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
                    window.sessionStorage.setItem( 'session_id', session_id );

                    success();
                },

                error: function( xhr, type ) {
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

    //info::({
    // username::string,
    // password::string,
    // regCode::string
    //}),
    //
    //scuuess::function,
    //
    //fail::function
    var doReg = function( info, success, fail ) {
    //{{{
        if( _.isEmpty( info.username ) ||
                _.isEmpty( info.password ) ||
                _.isEmpty( info.register_code ) )
        {
            throw new Error( 'param invalid' );
        } else {
            var xhr = $.post(
                config.serverAddress + 'auth_api/do_register/',
                info
            );

            xhr.done( function( data ) {
                if( _.isFunction( success ) ) {
                    success( data );
                }
            });

            xhr.fail( function( xhr ) {
                if( _.isFunction( fail ) ) {
                    fail( xhr );
                }
            });
        }
    };//}}}

    var doApply = function( info, success, fail ) {
    //{{{
        if( _.isEmpty( info.shopkeeper_name ) ||
                _.isEmpty( info.shopkeeper_tel ) ||
                _.isEmpty( info.shop_address ) ||
                _.isEmpty( info.shop_name ) )
        {
           throw new Error( 'param invalid' );
        } else {
            var xhr = $.post(
                config.serverAddress + 'apply_api/do_apply/',
                info
            );

            xhr.done( function( data ) {
                window.sysNotice.setMsg( '申请提交成功,请耐心等待审核' );
            });

            xhr.fail( function( xhr ) {
                window.sysNotice.setMsg( '申请提交失败,请稍后再试一次' );
            });
        }
    };//}}}

    var auth = {
        doReg: doReg,
        doApply: doApply,
        doLogin: doLogin,
        doLogout: doLogout
    };

    return auth;
});

