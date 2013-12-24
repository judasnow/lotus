define([

    'zepto',
    'underscore',
    'backbone'

], function( $, _, Backnone ) {
    'use strict';

    var Dropdown = Backnone.View.extend({

        className: 'dropdown',
        events: {
        },

        // ( args::{
        //  宿主元素
        //  $host::array,
        //  需要显示的模板
        //  tpl::string,
        //
        //  events::(object|undefined)
        // }) => void
        initialize: function( args ) {
        //{{{
            this._template = args.tpl;
            this._$host = args.$host;

            _.bindAll(
                this,

                'render',
                'show',
                'close'
            );

            this.render();
        },//}}}

        _onMouseleave: function( event ) {
            $( 'body' ).on( 'click', function( event ) {
                var $target = $( event.target );

                if ( $target.parents( '.dropdown' ).length === 0
                    && ! $target.is( '.dropdown' )
                ) {
                    //点击区域在 dropdown 之外
                    that.close();
                }
            });
        },

        show: function() {
            console.dir( 'show it' );
            var that = this;

            this.$el.show();

        },

        close: function() {
        //{{{
            console.dir( 'close it' );
            this.$el.hide();
        },//}}}

        render: function() {
        //{{{
            this.$el.html( this._template );
            this._$host.append( this.$el );
        }//}}}
    });

    return Dropdown;
});

