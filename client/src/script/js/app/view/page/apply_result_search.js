define([

    'zepto',
    'underscore',
    'backbone',
    'mustache',

    'utilities/auth',
    'utilities/page',

    'text!tpl/page/apply_result_search.mustache'

] , function( 
    $,
    _,
    Backbone,
    Mustache,
    auth,
    page,
    ApplyResultSearchTpl
) {
    'use strict';

    var ApplyResultSearchPageView = Backbone.View.extend({
        id: 'apply-result-search-page',
        className: 'box',

        events: {
            'click .submit': '_doSearch'
        },

        template: ApplyResultSearchTpl,

        initialize: function() {
            _.bindAll( this, 'render', '_getEls', '_doSearch' );
            this.render();
        },

        _getEls: function() {
            this._$keeperInput = this.$el.find( 'input[name="keeper_tel"]' );
            this._$errorInfo = this.$el.find( '.error_info' );
            this._$resInfo = this.$el.find( '.res_info' );
        },

        _doSearch: function() {
            var keeperTel = this._$keeperInput.val();
            var that = this;

            if( _.isEmpty( keeperTel ) ) {
                this._$errorInfo.text( '作为查询凭证的电话不能为空' );
            } else {
                auth.applyResultSearch({
                    shopkeeper_tel: keeperTel
                },
                function( data ) {
                    if( data.indexOf( '已经' ) !== -1 ) {
                        that._$resInfo
                            .removeClass( 'error_info' )
                            .addClass( 'ok_info' );
                    } else {
                        that._$resInfo
                            .removeClass( 'ok_info' )
                            .addClass( 'error_info' );
                    }

                    that._$resInfo.text( data );
                });
            }
        },

        render: function() {
            var that = this;
            this.$el.html( this.template );

            page.loadPage( this.$el, function() {
                that._getEls();
            });
        }
    });

    return ApplyResultSearchPageView;
});

