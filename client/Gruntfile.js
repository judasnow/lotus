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
                    './index.m.html': './src/preprocess_tpl/index.m.html',
                    './src/script/js/config.js': './src/preprocess_tpl/config.js'
                }
            },
            prod : {
                files: {
                    './index.raw.html': './src/preprocess_tpl/index.html',
                    './index.m.raw.html': './src/preprocess_tpl/index.m.html',
                    './src/script/js/config.js': './src/preprocess_tpl/config.js'
                }
            }
        },//}}}

        clean: {
        //{{{
            build: [ './build/*', './build/*.*' , './index.raw.html' ],
            raw_html: [ './index.raw.html', './index.m.raw.html' ]
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
                },{
                    src: './build/script/js/main-m-build.js',
                    dest: './build/script/js/main-m-build.min.js',
                }]
            }
        } ,//}}}

        less: {
        //{{{
            main: {
                options: {
                    compress: false
                } ,
                files: [{
                    src: './src/style/less/main.less',
                    dest: './src/style/css/main.css'
                },{
                    src: './src/style/less/main.m.less',
                    dest: './src/style/css/main.m.css'
                }]
            }
        },//}}}

        cssmin: {
        //{{{
            combine: {
                files: [{
                    './build/style/css/all.min.css': 
                    [
                        './src/style/css/main.css',
                        './src/style/third_party/fontawesome/css/font-awesome.css'
                    ]
                },{
                    './build/style/css/all.m.min.css': 
                    [
                        './src/style/css/main.m.css',
                        './src/style/third_party/fontawesome/css/font-awesome.css'
                    ]
                }]
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

        requirejs_: {
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

        requirejs: {
        //{{{
            compile: {
                options: {
                    optimize: "uglify",
                    uglify: {
                        comments: false
                    },
                    baseUrl: './src/script/',
                    mainConfigFile: './src/script/js/main.m.js',
                    name: 'js/main.m',
                    optimize: "uglify",
                    out: './build/script/js/main-m-build.js'
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
                files: [{
                    './index.html': './index.raw.html',
                    './index.m.html': './index.m.raw.html'
                }]
            }
        },//}}}

        nodewebkit: {
        //{{{
            options: {
                version: "0.8.4",
                build_dir: './webkitbuilds',
                mac: false,
                win: true,
                linux32: false,
                linux64: false
            },
            src: [
                'build/**/*',
                'picture/**/*',
                'picture/common/icon.png',
                'index.html',
                'package.json',
            ]
        }//}}}
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
    grunt.loadNpmTasks( 'grunt-node-webkit-builder' );

    grunt.registerTask(
        'dev',
        ['env:dev', 'clean', 'preprocess:dev']
    );

    grunt.registerTask( 
        'prod',
        ['env:prod', 'clean', 'preprocess:prod', 'less', 'cssmin', 'copy', 'uglify:main', 'requirejs', 'uglify:main_build', 'htmlmin', 'clean:raw_html' ]
    );

    grunt.registerTask(
        'm_prod',
        [
            'requirejs', 'uglify:main_build'
        ]
    );
};

