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
        var navView = views.navView;
        var footerView = views.footerView;

        var e = _.extend( {}, Backbone.Events );

        e.on( 'show_loading', navView.showLoading );
        e.on( 'hide_loading', navView.hideLoading );

        //對 home page 設置特殊的 footer
        e.on( 'load_home_page', function() {
            footerView.model.set( 'isHomePage', true );
        });

        e.on( 'login_ok', function( cb ) {
        //{{{
            common.log( 'login_ok fire' );

            common.getObjectUserInfo( function( objectUserInfo ) {
                //刷新頁首
                navView.model.set(
                    'objectUserInfo',
                    objectUserInfo.toJSON() 
                );
                footerView.model.set({
                    isLogin: true
                });

                cb();
            });
        });//}}}

        e.on( 'logout_ok', function() {
        //{{{
            common.log( 'logout_ok fire' );

            navView.model.set(
                'objectUserInfo',
                null
            );
            footerView.model.set({
                isLogin: false
            });
        });//}}}

        this.e = e;
    };

    return GlobleEvents;
});
