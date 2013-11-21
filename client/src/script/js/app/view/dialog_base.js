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

        closeDialog: function() {
            this.$el.hide();
        },

        showDialog: function() {
            this.$el.show();
        },

    });

    return DialogViewBase;
});

