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
            var that = this;
            this._template = args.tpl;
            this._$host = args.$host;

            _.bindAll(
                this,

                'render',
                'show',
                'close'
            );

            this.render();

            //初次点击
            this._$host.one( 'click', function( event ) {
                event.stopPropagation();
                that.show();
            });
        },//}}}

        show: function( event ) {
            var that = this;
            this.$el.show();

            $( 'body' ).one( 'click', function( event ) {
                var $target = $( event.target );

                if ( $target.parents( '.dropdown' ).length === 0
                    && ! $target.is( '.dropdown' )
                ) {
                    //点击区域在 dropdown 之外
                    that.close( event );
                }
            });

            this.$el.one( 'click', '.menuitem', function( event ) {
                event.stopPropagation();
                that.close( event );
            });
        },

        close: function( event ) {
        //{{{
            var that = this;
            this.$el.hide();

            this._$host.one( 'click', function( event ) {
                console.dir( event )
                event.stopPropagation();
                that.show( event );
            });
        },//}}}

        render: function() {
        //{{{
            this.$el.html( this._template );
            this._$host.append( this.$el );
        }//}}}
    });

    return Dropdown;
});

