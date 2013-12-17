define([
    'zepto',
    'underscore',
    'backbone',

    'text!tpl/search.mustache'
] , function(
    $ ,
    _ ,
    Backbone ,

    SearchTpl 
) {
    'use strict';

    var SearchView = Backbone.View.extend({

        template: SearchTpl,

        events: {
            'click .do-search': '_doSearch'
        },

        // ({
        //  $el::Array
        // }) => void
        initialize: function( args ) {
            this.$el = args.$el;

            _.bindAll( this , '_doSearch' , 'render' );

            this.render();
        },

        _doSearch: function() {
            var $q = this.$el.find( 'input[name="q"]' );
            var q = $q.val();

            if( typeof q === 'undefined' || q === '' ) {
                window.sysNotice.setMsg( '关键词不能为空' );
                return;
            }

            //默认先获取第一页
            //@XXX 过滤 q
            window.routes.navigate( '/search_result/' + q + '/1' , {trigger: true} );
        },

        render: function() {
            this.$el.html( this.template );
        }
    });

    return SearchView;
});

