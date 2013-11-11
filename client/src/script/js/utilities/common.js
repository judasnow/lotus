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

    //
    //@TODO 有可能需要将其抽象到一个 view 中
    //
    //@offset object 需要关联 dropdown 的元素的 位置信息
    //@id string @XXX 似乎是可有可无的 因为可以自动生成一个 现在的话 这个 id 是包含在了 tpl 中
    //@tpl string 
    //
    //@return $el
    var dropdown = function( offset , id , tpl ) {
    //{{{
        if( typeof offset !== "object" ||
            typeof offset.top === "undefined" ||
            typeof offset.left === "undefined" ||
            typeof id !== "string" ||
            typeof tpl !== "string"
        ) {
            throw new Error( "param invalid: " + arguments );
        }

        var idStr = "#" + id;
        if( $( idStr ).length !== 0 ) {
            throw new Error( "this id already in DOM" );
        }

        //@XXX did the zeptojs cache it ?
        $( "body" ).append( tpl );

        return $( idStr ).css({
            top: offset.height + offset.top , 
            left: offset.left
        });
    };//}}}

    common.getSessionId = getSessionId;
    common.getObjectUserInfo = getObjectUserInfo;
    common.dropdown = dropdown;

    return common;
});
