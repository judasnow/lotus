module.exports = function( grunt ) {
    grunt.initConfig({
        pkg: grunt.file.readJSON( "package.json" ) ,

        meta: {
        //{{{

        },//}}}

        env : {
        //{{{
            dev : {
                ENV : "dev"
            } ,
            prod : {
                ENV : "prod"
            }
        },//}}}

        preprocess : {
        //{{{
            dev : {
                src : "./src/preprocess_tpl/index.html",
                dest : "./index.html"
            } ,
            prod : {
                src : "./src/preprocess_tpl/index.html",
                dest : "./index.html",
            }
        },//}}}

        clean: {
        //{{{
            build: [ "./build/script/*.js" , "./build/script/lib/*.js" , "./build/style/css/*.css" , "petal!.mf" , "petal.mf" ]
        },//}}}

        uglify: {
        //{{{
            options: {
                //banner: '/*! <%= pkg.name %> <%= grunt.template.today( "yyyy-mm-dd" ) %> */\n'
            },
            main: {
                files: [{
                    expand: true,
                    cwd: "./src/script/",
                    src: ["*.js", "mvc/*.js" , "lib/*.js", "third_part/cubiq-add-to-homescreen/src/add2home.js" ],
                    dest: "./build/script/",
                    ext: ".js"
                }]
            }
        } ,//}}}

        cssmin: {
        //{{{
            combine: {
                files: {
                    "./build/style/css/main.css": ["./src/style/css/icons.css","./src/style/css/jq.ui.css","./src/style/css/main.css","./src/script/third_part/cubiq-add-to-homescreen/style/add2home.css","./src/style/css/font-awesome.css" ]
                }
            }
        },//}}}

        less: {
        //{{{
            options: {
                compress: false
            } ,
            main: {
                src: "./src/style/less/main.less" ,
                dest: "./src/style/css/main.css"
            }
        },//}}}

        concat: {
        //{{{
            js: {
                src: [
                    "./build/script/lib/appframework.js" ,
                    "./build/script/lib/jqmobiui.js" ,
                    "./build/script/init.js" ,
                    "./build/script/third_part/cubiq-add-to-homescreen/src/add2home.js"
                ] ,
                dest: "./build/script/all.js"
            } ,
            css: {
                
            }
        },//}}}

        requirejs: {
        //{{{
            app: {
                options: {
                    baseUrl: "./src/script/",
                    paths: {
                        app: "app" ,

                        //requirejs plugins
                        text: "lib/text" ,

                        //libs
                        underscore: "lib/underscore" ,
                        backbone: "lib/backbone" ,
                        mustache: "lib/mustache" ,
                        date_utils: "lib/date-utils" ,

                        router: "router" ,

                        tpl: "mvc/tpl" ,
                        v: "mvc/v" ,
                        m: "mvc/m" ,
                        c: "mvc/c"
                    },
                    shim: {
                        backbone: {
                            deps: [ "underscore" ],
                            exports: "Backbone",
                            init: function() {
                                Backbone.$ = window.$;
                            }
                        },
                        underscore: {
                            exports: "_"
                        },
                        mustache: {
                            exports: "Mustache"
                        },
                        date_utils: {
                            exports: "date_utils"
                        }
                    },
                    name: "main",
                    out: "./build/script/rquirejs_main_build.js"
                }
            }
        },//}}}

        htmlmin: {
        //{{{
            dist: {
                options: {
                    removeComments: true ,
                    collapseWhitespace: true
                } ,
                files: {
                    "./build/html/index.html": "index.html"
                }
            }
        },//}}}
    });

    grunt.loadNpmTasks( "grunt-env" );
    grunt.loadNpmTasks( "grunt-preprocess" );
    grunt.loadNpmTasks( "grunt-contrib-less" );
    grunt.loadNpmTasks( "grunt-contrib-uglify" );
    grunt.loadNpmTasks( "grunt-contrib-concat" );
    grunt.loadNpmTasks( "grunt-contrib-clean" );
    grunt.loadNpmTasks( "grunt-contrib-requirejs" );
    grunt.loadNpmTasks( "grunt-contrib-cssmin" );
    grunt.loadNpmTasks( "grunt-contrib-htmlmin" );

    grunt.registerTask( 
        "dev" ,
        ["env:dev" , "clean" , "preprocess:dev" ]
    );

    grunt.registerTask( 
        "prod" ,
        [ "env:prod" , "clean" , "preprocess:prod" ]
    );
};

