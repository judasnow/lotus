define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'async',

    'm/sys_notice',

    'text!tpl/sys_notice.mustache'

] , function(
    $ ,
    _ ,
    Backbone ,
    Mustache ,
    async ,

    SysNotice ,

    sysNoticeTpl 
) {
    'use strict';

    var SysNoticeView = Backbone.View.extend({
        template: sysNoticeTpl,
        el: '#sys-notice',

        initialize: function() {
            this._model = new SysNotice();

            _.bindAll( this , 'setMsg' , 'render' );

            this.listenTo( this._model , 'change' , this.render );
        },

        setMsg: function( msg ) {
            this._model.set( 'msg' , msg );
        },

        render: function() {
            var that = this;

            if( this._model.get( 'msg' ) === '' ) {
                return;
            }

            that.$el
                .show()
                .html( Mustache.to_html( this.template , this._model.toJSON() ) );

            setTimeout( function() {
                that.$el.hide();
                that._model.set( 'msg' , '' );
            } , 1000 );
        }
    });

    return SysNoticeView;
});

