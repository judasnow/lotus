define([
    'zepto',
    'underscore',
    'async',

    'm/user',

    'config'

] , function (

    $ ,
    _ ,
    async ,

    User ,

    config
) {
    'use strict';

    //( void ) => boolean
    var isLogin = function() {
        return ! ( common.getSessionId() === null );
    };

    //独立出来是为了方便之后的处理
    //(void) => number | null
    var getSessionId = function() {
    //{{{
        var sessionId = window.sessionStorage.getItem( 'session_id' );

        return sessionId;
    };//}}}

    //获取并绑定当前登录用户信息到 window 对象上
    //(function) => void
    var getObjectUserInfo = function( success ) {
    //{{{
        var objectUser = new User();

        objectUser.fetch({
            data: {
                session_id: common.getSessionId()
            },
            success: function() {
                window.objectUser = objectUser;

                success( objectUser );
            }
        });
    };//}}}

    //上传文件
    //(file , string , success) => callback()
    var uploadFile = function( file , targetUrl , success ) {
    //{{{
        if( typeof file === 'object' && typeof targetUrl === 'string' ) {
            var xhr = new XMLHttpRequest();
            var formData = new FormData();

            formData.append( 'userfile' , file );
            formData.append( 'image_type' , 'product' );
            formData.append( 'session_id' , getSessionId() );

            xhr.onload = function( xhr ) {
                success( this.responseText );
            };

            xhr.open( 'POST' , config.serverAddress + targetUrl );
            xhr.send( formData );
        }
    };//}}}

    var log = function( text , color ) {
    //{{{
        if( typeof color === 'undefined' ) {
            color = '#f99';
        }

        //console.log( '%c ' + text , 'color:' + color );
    }//}}}

    //显示系统默认样式的提示信息
    var showNotice = function( msg ) {
        
    };

    var common = {
        isLogin: isLogin,
        log: log,
        getSessionId: getSessionId,
        getObjectUserInfo: getObjectUserInfo,
        uploadFile: uploadFile,
        showNotice: showNotice
    }

    return common;
});

