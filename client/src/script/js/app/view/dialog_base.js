// @TODO 使用本类的目的在于 复用基本的 dialog 操作
// 是否存在更好的做法？
define([

    "zepto",
    "backbone"

] , function( $ , Backbone ) {
    "use strict";

    var DialogViewBase = Backbone.View.extend({

        _baseEvents: {
            "click .close": "closeDialog",
        },

        _baseInit: function() {
            _.bindAll( this , "closeDialog" , "showDialog" );
        },

        //@XXX parent() 元素要是 dialog_wrapper 才能使用 unwrap
        closeDialog: function() {
            this.$el.unwrap().hide();
        },

        showDialog: function() {
            this.$el.wrap( '<div class="dialog_wrapper" />' ).show();
        },

        toggle: function() {
            this.$el.wrap( '<div class="dialog_wrapper" />' ).toggle();
        },

        baseRender: function() {
            
        }

    });

    return DialogViewBase;
});

