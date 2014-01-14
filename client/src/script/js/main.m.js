requirejs.config({
    baseUrl: './src/script/' ,

    paths: {
        text: 'third_party/text',

        zepto: 'third_party/zepto' ,
        underscore: 'third_party/underscore' ,
        backbone: 'third_party/backbone',
        mustache: 'third_party/mustache',
        async: 'third_party/async',
        q: 'third_party/q',

        m: 'js/app/model',
        v: 'js/app/view',
        c: 'js/app/collection',
        tpl: 'js/app/tpl',

        utilities: 'js/utilities',
        routes: 'js/routes.m',
        config: 'js/config',
        global_events: 'js/global_events'
    },

    shim: {
        zepto: {
            exports: '$'
        } ,
        underscore: {
            exports: '_'
        } ,
        backbone: {
            deps: ['underscore', 'zepto'],
            exports: 'Backbone'
        }
    }
});

//main
requirejs([
    'zepto',
    'underscore',
    'async',

    'v/slider.m',

    'routes'
], function (
    $,
    _,
    async,

    Slider,
    Routes
) {
    'use strict';

    var routes = new Routes();
    window.routes = routes;
    Backbone.history.start();

    $('#header').find('.fa').on('singleTap', function() {
        $('.slider').toggleClass('slider-show');
    });

    new Slider();
});

