define([

    "backbone",
    "mustache",

    "c/class_b",

    "text!tpl/class_b_list_item.mustache"

] , function(

    Backbone,
    Mustache,

    ClassBColl,

    classBListItemTpl
) {
    "use strice";

    var ClassBListView = Backbone.View.extend({

        className: "categories_detail",
        tagName: "div",

        initialize: function( $categoriesBrowse , class_a_id ) {
            _.bindAll( this , "_addAll" , "_addOne" , "_postShowList" , "_hide" , "render" );

            this._class_a_id = class_a_id;
            this._$categoriesBrowse = $categoriesBrowse;

            var that = this;
            this._$categoriesBrowse.on( "mouseleave" , function( event ) {
                that._hide.bind( that )();
            });

            this._classBColl = new ClassBColl( class_a_id );
            this._classBColl.on( "fetch_ok" , this._addAll );

            this._classBColl.fetch({
                success: function( coll ) {
                    coll.trigger( "fetch_ok" );
                }
            })
        },

        //进行一些显示之前列表的准备工作
        //@TODO 缓存之
        _postShowList: function() {
            var $categoriesDetail = this._$categoriesBrowse.find( ".categories_detail" );
            if( $categoriesDetail.length !== 0 ) {
                $categoriesDetail.remove();
            } else {
                this._$categoriesBrowse.css( "width" , "680px" );
            }
        },

        _addAll: function() {
            this._classBColl.each( this._addOne );
            this.render();
        },

        _addOne: function( item ) {
            this.$el.append( Mustache.to_html( classBListItemTpl , item.toJSON() ) );
        },

        _hide: function() {
            this.$el.remove();
            this._$categoriesBrowse.css( "width" , "auto" );
        },

        render: function() {
            this._postShowList();
            this._$categoriesBrowse.append( this.$el );
        }
    });

    return ClassBListView;
})
