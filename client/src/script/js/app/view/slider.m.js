define([
    'zepto',
    'underscore',
    'backbone',
    'mustache',
    'async',

    'c/class_a',
    'c/class_b',

    'utilities/common',
    'text!tpl/slider_items.m.mustache',
    'text!tpl/slider_item.m.mustache'

] , function(
    $,
    _,
    Backbone,
    Mustache,
    async,

    ClassAColl,
    ClassBColl,

    common,
    sliderItemsTpl,
    sliderItemTpl
) {
    'use strict';

    var Slider = Backbone.View.extend({
        el: '#main-box .slider',
        events: {
            'click .class-b': '_goStream'
        },
        initialize: function() {
            _.bindAll(this, '_renderClassA');

            this._classAColl = new ClassAColl();
            this.listenTo(this._classAColl, 'reset', this._renderClassA);

            this.render();
        },

        _goStream: function(event) {
            var $target = $(event.currentTarget);
            var class_b_id = $target.attr('data-id');
            var $currentEl = $target;
            while ($currentEl.prev('.title-item').length === 0) {
                $currentEl = $currentEl.prev();
            }
            var class_a_id = $currentEl.prev('.title-item').attr('data-id');

            window.routes.navigate('stream/' + class_a_id + '/' + class_b_id, {trigger: true});
        },

        _renderClassA: function(coll) {
            this.$el.append(
                Mustache.to_html(
                    sliderItemsTpl,
                    {
                        items: coll.toJSON()
                    }
                )
            );

            this.$el.find('.title-item').each(function(index, item) {
                var $item = $(item);
                var classBColl = new ClassBColl();

                classBColl.fetch({
                    data: {class_a_id: $item.attr('data-id')},
                    success: function(classBColl) {
                        classBColl.each(function(classBItem) {
                            $item.after(Mustache.to_html(sliderItemTpl, classBItem.toJSON()));
                        })
                    }
                })
            });
        },

        render: function() {
            this._classAColl.fetch({
                data: {},
                reset: true
            });
        }
    });

    return Slider;
});
