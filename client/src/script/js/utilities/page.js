define([

    'zepto',
    'underscore',
    'async'

], function( $ , _ , async ) {
    'use strict';

    var $wrapper = $( '#wrapper' );

    var _fadeOutPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 0
        }, {
            duration: 100,
            complete: cb
        });
    };//}}}

    var _fadeInPage = function( cb ) {
    //{{{
        $wrapper.animate({
            opacity: 100
        }, {
            duration: 50,
            complete: cb
        });
    };//}}}

    // 需要注意的地方就是 page 变换 替换的是 box 部分的内容
    var loadPage = function( $el , success ) {
    //{{{
        async.series([
            function( cb ) {
                _fadeOutPage( cb );
            },
            function( cb ) {
                $wrapper.html( $el );
                cb();
            },
            function( cb ) {
                _fadeInPage( cb );

                if( _.isFunction( success ) ) {
                    success();
                }
            }
        ], function( err , res ) {
            //@TODO
        });
    };//}}}

    //获取 page 中的指定元素引用
    //{ "elName::string" : "selector::string" } => { elName: $( selector ) }
    var getEls = function( selectors ) {
    //{{{
        var that = this;

        if ( ! _.isObject( selectors ) ) {
            throw new Error( 'invalid param' );
        } else {
            return _.reduce( selectors, function( els, selector, elName ) {
                els[ elName ] = that.$el.find( selector );
                return els;
            }, {});
        }
    };//}}}

    //{ valName::string, $input::$() } => { name: string }
    var getInputsVal = function( $inputs ) {
    //{{{
        var that = this;

        if( ! _.isObject( $inputs ) ) {
            throw new Error( 'invalid param' );
        } else {
            return _.reduct( $inputs, function( vals, $input, valName ) { 
                vals[ valName ] = $input.val();
                return vals;
            }, {});
        }
    };//}}}

    var page = {
        getEls: getEls,
        getInputsVal: getInputsVal,
        loadPage: loadPage
    };

    return page;
});

