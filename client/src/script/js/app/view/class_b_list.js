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

        events: {
            'click .row': '_goProductListByClassPage'
        },

        initialize: function( $categoriesBrowse , classAId , classAContent ) {
        //{{{
            _.bindAll( this , "_addAll" , "_addOne" , "_postShowList" , "_hide" , "render" );

            this._classAId = classAId;
            this._classAContent = classAContent;
            this._$categoriesBrowse = $categoriesBrowse;

            var that = this;
            this._$categoriesBrowse.on( "mouseleave" , function( event ) {
                that._hide.bind( that )();
            });

            this._classBColl = new ClassBColl( classAId );
            this._classBColl.on( "fetch_ok" , this._addAll );

            this._classBColl.fetch({
                success: function( coll ) {
                    coll.trigger( "fetch_ok" );
                }
            });

            this.render();
        },//}}}

        //进行一些显示之前列表的准备工作
        //@TODO 缓存之
        _postShowList: function() {
        //{{{
            var $categoriesDetail = this._$categoriesBrowse.find( ".categories_detail" );
            if( $categoriesDetail.length !== 0 ) {
                $categoriesDetail.remove();
            } else {
                this._$categoriesBrowse.css( "width" , "680px" );
            }
        },//}}}

        _goProductListByClassPage: function( e ) {
            var $row = $( e.currentTarget );
            var classBId = $row.attr( 'data-id' );
            var classBContent = $row.find( '.text' ).text();

            //@TODO url encode
            window.routes.navigate(
                '/product_list_by_class/' + this._classAId + '/' + classBId,
                {
                    trigger: true
                }
            );
        },

        _addAll: function() {
        //{{{
            this._classBColl.each( this._addOne );
        },//}}}

        _addOne: function( item ) {
        //{{{
            this.$el.append( Mustache.to_html( classBListItemTpl , item.toJSON() ) );
        },//}}}

        _hide: function() {
        //{{{
            this.$el.remove();
            this._$categoriesBrowse.css( "width" , "auto" );
        },//}}}

        render: function() {
        //{{{
            this._postShowList();
            this._$categoriesBrowse.append( this.$el );
        }//}}}
    });

    return ClassBListView;
})
