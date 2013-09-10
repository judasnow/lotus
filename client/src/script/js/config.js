require.config({
    baseUrl: "./src/script/" ,
    paths: {
        //third party
        //{{{
        jquery: "third_party_lib/jquery" ,
        underscore: "third_party_lib/underscore" ,
        backbone: "third_party_lib/backbone" ,
        //}}}
    } ,
    shim: {
        jquery: {
            exports: "$"
        } ,
        underscore: {
            exports: "_"
        } ,
        backbone: {
            deps: ["underscore", "jquery"],
            exports: "Backbone"
        }
    }
});
