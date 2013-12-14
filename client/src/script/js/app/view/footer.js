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
            this.model = new FooterModel();

            _.bindAll( this , 'render' );

            this.listenTo( this.model, 'change', this.render );
            this.model.set( 'isLogin' , common.isLogin() );
        },

        render: function() {
            this.$el.html(
                Mustache.to_html(
                    footerTpl ,
                    this.model.toJSON()
                ) 
            );
        }

    });

    return FooterView;
})
