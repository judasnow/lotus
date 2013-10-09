require.config({
    baseUrl: "./src/script/" ,
    paths: {
        //third party
        //{{{
        zepto: "third_party/zepto" ,
        underscore: "third_party/underscore" ,
        backbone: "third_party/backbone" 
        //}}}
    } ,
    shim: {
        zepto: {
            exports: "$"
        } ,
        underscore: {
            exports: "_"
        } ,
        backbone: {
            deps: ["underscore", "zepto"],
            exports: "Backbone"
        }
    }
});

require(
[
    "zepto"
] ,
function( $ ) {
    console.log( "%c init ok" , "color: #090" );
});
