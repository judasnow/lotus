define([
    "zepto"
] , function () {
    "use strict";

    var common = {};

    //独立出来是为了方便之后的处理
    var getSessionId = function() {
        var sessionId = window.sessionStorage.getItem( "session_id" );
        return sessionId;
    };

    common.getSessionId = getSessionId;

    return common;
});
