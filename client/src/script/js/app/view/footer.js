define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'm/footer',

    'utilities/common',

    'text!tpl/footer.mustache'

] , function(

    $,
    _,
    Backbone,
    Mustache,

    FooterModel,

    common,

    footerTpl
) {
    'use strict';

    var FooterView = Backbone.View.extend({

        el: '#footer',
        className: 'box',

        initialize: function() {
            this._model = new FooterModel();

            _.bindAll( this , 'render' );

            this._model.on( 'change' , this.render );
            this._model.set( 'is_login' , common.isLogin() );
        },

        render: function() {
            this.$el.html(
                Mustache.to_html(
                    footerTpl ,
                    this._model.toJSON()
                ) 
            );
        }

    });

    return FooterView;
})
