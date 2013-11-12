define([

    "zepto",
    "underscore",
    "backbone"
    
] , function( $ , _ , Backnone ) {
    "use strict";

    var Dropdown = Backnone.View.extend({

        tagName: "nav",
        className: "dropdown",

        //@offset object 需要关联 dropdown 的元素的 位置信息
        //@id string @XXX 似乎是可有可无的 因为可以自动生成一个 现在的话 这个 id 是包含在了 tpl 中
        //@tpl string
        //@events object 
        initialize: function( offset , id , tpl , events ) {
        //{{{
            if( typeof offset !== "object" ||
                typeof offset.top === "undefined" ||
                typeof offset.left === "undefined" ||
                typeof id !== "string" ||
                typeof tpl !== "string"
            ) {
                throw new Error( "param invalid: " + arguments );
            }

            if( typeof events === "object" ) {
                this.events = events;
            }

            _.bindAll( this , "render" );

            this._offset = offset;
            this.tpl = tpl;

            var idStr = "#" + id;
            if( $( idStr ).length !== 0 ) {
                throw new Error( "this id already in DOM" );
            }

            this.id = id;

            this.render();
        },//}}}

        render: function() {
        //{{{
            this.$el.html( this.tpl );
            this.$el.attr( "id" , this.id );

            $( "body" ).append( this.$el );

            this.$el.css({
                top: this._offset.height + this._offset.top ,
                left: this._offset.left
            });
        }//}}}
    });

    return Dropdown;
});
