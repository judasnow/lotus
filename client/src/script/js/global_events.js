define([
    'zepto',
    'underscore',
    'backbone',

    'utilities/common',

    'v/nav'

], function(
    $,
    _,
    Backbone,

    common
) {
    'use strict';

    // 全局的事件對象
    // 設想的是可以通過綁定在 window 對象上的該對象
    // 觸發一些全局的通用事件( 這樣就可以把這些通用事件對應的處理函數放在一起 )
    //
    // ( object ) => void
    // 其中 object 對應的是 需要用到的 view 對象
    var GlobleEvents = function( views ) {
        //@TODO macro it
        var navView = views.nav_view;

        var e = _.extend( {}, Backbone.Events );

        e.on( 'show_loading', navView.showLoading );
        e.on( 'hide_loading', navView.hideLoading );

        e.on( 'login_ok', function() {
            common.log( 'login_ok fire' , 'blue' );

            common.getObjectUserInfo( function( objectUserInfo ) {
                //刷新頁首
                navView.model.set( 'objectUserInfo' , objectUserInfo.toJSON() );
            });
        });
        e.trigger( 'login_ok' );

        e.on( 'logout_ok', function() {
            console.log( 'logout_ok fire' );
        });

        this.e = e;
    };

    return GlobleEvents;
});
