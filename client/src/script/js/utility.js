define([

    "zepto"

], function( $ ) {
    "use strict";

    var utility = {};

    var _$body = $( "body" );

    //当前实现的思路就是 完全替换 body 中的元素
    //为给定的 html 文本信息
    utility.loadPage = function( html ) {
        _$body.html( html );
    };

    utility.showLoading = function() {
        
    };

    return utility;
});
