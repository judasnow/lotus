define([

    "zepto",
    "async",

    "m/user"

] , function (

    $ ,
    async,

    User
) {
    "use strict";

    var common = {};

    //独立出来是为了方便之后的处理
    var getSessionId = function() {
    //{{{
        var sessionId = window.sessionStorage.getItem( "session_id" );
        return sessionId;
    };//}}}

    //获取并绑定当前登录用户信息到 window 对象上
    //@param success function
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

    common.getSessionId = getSessionId;
    common.getObjectUserInfo = getObjectUserInfo;

    return common;
});

