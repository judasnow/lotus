define([
    "zepto",
    "async",

    "m/user",

    'config'

] , function (

    $ ,
    async,

    User,

    config
) {
    "use strict";

    //独立出来是为了方便之后的处理
    //(void) => number | null
    var getSessionId = function() {
    //{{{
        var sessionId = window.sessionStorage.getItem( "session_id" );
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
                success();
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

            xhr.open( "POST" , config.serverAddress + targetUrl );
            xhr.send( formData );
        }
    };//}}}

    var common = { 
        getSessionId: getSessionId,
        getObjectUserInfo: getObjectUserInfo,
        uploadFile: uploadFile
    }

    return common;
});

