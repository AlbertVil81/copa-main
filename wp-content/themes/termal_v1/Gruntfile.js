'use strict';
module.exports = function(grunt) {

    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    const sass = require('node-sass');

    grunt.initConfig({

        watch: {
            js: {
                files: ['assets/scripts/functions.js', 'assets/scripts/modules/**/*.js'],
                tasks: ['assets_version', 'babel', 'concat:js', 'uglify']
            },
            css: {
                files: ['assets/scss/**/*.scss', 'assets/scss/pages/*.scss'],
                tasks: ['assets_version', 'sass', 'postcss', 'concat:css', 'cssmin'],
                options: {
                    spawn: false
                }
            },
            php: {
                files: ['*.php', 'page-templates/**/*.php'],
                tasks: ['copy:main']
            }
        },

        sass: {
            dist: {
                options: {
                    implementation: sass,
                    outputStyle: 'expanded',
                    sourceMap: true,
                    quiet: true // stop depreciation errors
                },
                files: [{
                    expand: true,
                    cwd: 'assets/scss',
                    src: 'styles.scss',
                    dest: 'dist/assets/css',
                    ext: '.css'
                }, {
                    expand: true,
                    cwd: 'assets/scss',
                    src: 'styles.scss',
                    dest: 'assets/css',
                    ext: '.css'
                }]
            }
        },

        postcss: { // Begin Post CSS Plugin
            options: {
                map: false,
                processors: [require('autoprefixer')({ overrideBrowserslist: ['last 2 version'] })]
            },
            dist: {
                src: 'assets/css/styles.css'
            }
        },

        copy: {
            main: {
                files: [
                    // includes files within path and its sub-directories
                    { expand: true, src: ['style.css'], dest: 'dist/' },
                    { expand: true, src: ['*.php'], dest: 'dist/' },
                    { expand: true, src: ['assets.version'], dest: 'dist/' },
                    { expand: true, src: ['screenshot.png'], dest: 'dist/' },
                    { expand: true, src: ['page-templates/*'], dest: 'dist/' },
                    { expand: true, src: ['includes/*'], dest: 'dist/' },
                    { expand: true, src: ['assets/fonts/*'], dest: 'dist/' },
                    { expand: true, src: ['assets/css/config.rb'], dest: 'dist/' },
                    { expand: true, src: ['assets/css/fonts/*'], dest: 'dist/' },
                    { expand: true, src: ['assets/img/*'], dest: 'dist/' },
                    { expand: true, src: ['assets/img/**/*'], dest: 'dist/' },
                    {expand: true, src: ['assets/favicon/*'], dest: 'dist/'},
                    {expand: true, src: ['vendor/**/*'], dest: 'dist/'},
                    {expand: true, src: ['assets/css/ajax-loader.gif'], dest: 'dist/'},
                ],
            },
        },

        concat: {
            options: {
                sourceMap: true,
                sourceMapStyle: 'link'
            },
            css: {
                src: [
                    'assets/css/dropify.css', 'assets/css/jquery.skeleton.css', 'assets/css/styles.css', 'assets/css/slick-theme.css', 'assets/css/slick.css'
                ],
                dest: 'combined/combined.css'
            },
            js: {
                src: [
                    'assets/scripts/jquery-3.6.0.min.js', 'assets/scripts/jquery.validate.js', 'assets/scripts/validation/additional-methods.js', 'assets/scripts/materialize.min.js', 'assets/scripts/slick.js', 'assets/scripts/slick.min.js', 'assets/scripts/circle-progress.min.js', 'assets/scripts/dropify.js', 'assets/scripts/jquery.scheletrone.js', 'assets/scripts/datedropper-jquery.js', 'assets/scripts/jquery.waypoints.min.js', 'assets/scripts/functions.js', 'assets/scripts/modules/*.js'
                ],
                dest: 'combined/combined.js'
            }
        },

        cssmin: {
            css: {
                src: 'combined/combined.css',
                dest: 'dist/assets/css/app.css'
            },
            css_local: {
                src: 'combined/combined.css',
                dest: 'assets/css/app.css'
            }
        },

        uglify: {
            js: {
                files: {
                    'dist/assets/scripts/app.js': ['combined/combined.js']
                }
            },
            js_local: {
                files: {
                    'assets/scripts/app.js': ['combined/combined.js']
                }
            }
        },

        // image optimization
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 7,
                    progressive: true
                },
                files: [{
                        expand: true,
                        cwd: 'assets/images/',
                        src: '**/*',
                        dest: 'dist/assets/img/'
                    },
                    {
                        expand: true,
                        cwd: 'assets/images/',
                        src: '**/*',
                        dest: 'assets/img/'
                    }
                ]
            }
        },

        babel: {
            options: {
                sourceMap: true
            },
            dist: {
                options: {
                    sourceMap: true,
                    inputSourceMap: grunt.file.readJSON('combined/combined.js.map'),
                },
                files: {
                    'assets/scripts/starter_babel.js': 'assets/scripts/modules/starter.js'
                }
            }
        },

        clean: {
            combined: ["combined"],
            dist: ["dist"]
        }

    });

    // register task
    grunt.registerTask('default', ['clean:dist', 'assets_version', 'sass', 'postcss', 'concat', 'cssmin', 'babel', 'uglify', 'copy']);

    grunt.registerTask('images', ['imagemin']);

    grunt.registerTask('assets_version', 'Generate a new assets.version file for cache busting', function() {
        var date = Date.now();
        grunt.file.write('assets.version', date);
    });

};