module.exports = function( grunt ) {
    grunt.initConfig({
        pkg: grunt.file.readJSON( 'package.json' ) ,

        meta: {
        //{{{

        },//}}}

        env : {
        //{{{
            dev : {
                ENV : 'dev'
            } ,
            prod : {
                ENV : 'prod'
            }
        },//}}}

        preprocess : {
        //{{{
            dev : {
                files: {
                    './index.html': './src/preprocess_tpl/index.html',
                    './src/script/js/config.js': './src/preprocess_tpl/config.js'
                }
            } ,
            prod : {
                files: {
                    './index.html': './src/preprocess_tpl/index.html',
                    './src/script/js/config.js': './src/preprocess_tpl/config.js'
                }
            }
        },//}}}

        clean: {
        //{{{
            build: [ './build/*', './build/*.*' , './index.raw.html' ],
            raw_html: [ './index.raw.html' ]
        },//}}}

        uglify: {
        //{{{
            options: {
                comments: false
            },
            main: {
                files: [{
                    expand: true,
                    cwd: './src/script/',
                    src: '**/*.js',
                    dest: './build/script/',
                    ext: '.min.js'
                }]
            },
            main_build: {
                files: [{
                    src: './build/script/js/main-build.js',
                    dest: './build/script/js/main-build.min.js',
                }]
            }
        } ,//}}}

        less: {
        //{{{
            options: {
                compress: false
            } ,
            main: {
                src: './src/style/less/main.less' ,
                dest: './src/style/css/main.css'
            }
        },//}}}

        cssmin: {
        //{{{
            combine: {
                files: {
                    './build/style/css/all.min.css': 
                    [
                        './src/style/css/main.css',
                        './src/style/third_party/fontawesome/css/font-awesome.css'
                    ]
                }
            }
        },//}}}

        copy: {
        //{{{
            font: {
                files: [{
                    expand: true,
                    cwd: './src/style/third_party/fontawesome/fonts/',
                    src: ['*.*'],
                    dest: './build/style/fonts/'
                }]
            }
        },//}}}

        requirejs: {
        //{{{
            compile: {
                options: {
                    optimize: "uglify",
                    uglify: {
                        comments: false
                    },
                    baseUrl: './src/script/',
                    mainConfigFile: './src/script/js/main.js',
                    name: 'js/main',
                    optimize: "uglify",
                    out: './build/script/js/main-build.js'
                }
            }
        },//}}}

        htmlmin: {
        //{{{
            dist: {
                options: {
                    removeComments: false ,
                    collapseWhitespace: true
                },
                files: {
                    './index.html': './index.raw.html'
                }
            }
        },//}}}
    });

    grunt.loadNpmTasks( 'grunt-env' );
    grunt.loadNpmTasks( 'grunt-preprocess' );
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-concat' );
    grunt.loadNpmTasks( 'grunt-contrib-clean' );
    grunt.loadNpmTasks( 'grunt-contrib-requirejs' );
    grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
    grunt.loadNpmTasks( 'grunt-contrib-htmlmin' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    grunt.registerTask(
        'dev',
        ['env:dev', 'clean', 'preprocess:dev']
    );

    grunt.registerTask( 
        'prod',
        ['env:prod', 'clean', 'preprocess:prod', 'less', 'cssmin', 'copy', 'uglify:main', 'requirejs', 'uglify:main_build', 'htmlmin', 'clean:raw_html' ]
    );
};

