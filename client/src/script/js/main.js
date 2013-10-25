require.config({
    baseUrl: "./src/script/" ,
    paths: {
        text: "third_party/text",

        zepto: "third_party/zepto" ,
        underscore: "third_party/underscore" ,
        backbone: "third_party/backbone",
        mustache: "third_party/mustache",

        m: "js/app/model",
        v: "js/app/view",
        c: "js/app/collection",
        tpl: "js/app/tpl"
    },

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
    "zepto",

    "v/categories_browse"
] ,
function(
    $,

    CategoriesBrowseView
) {
    //categories_browse
    var categoriesBrowseView = new CategoriesBrowseView();
});
