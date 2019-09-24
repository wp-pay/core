/**
 * Grunt tasks.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

module.exports = function( grunt ) {
	require( 'load-grunt-tasks' )( grunt );

	// Project configuration.
	grunt.initConfig(
		{
			// Package.
			pkg: grunt.file.readJSON( 'package.json' ),

			// Copy.
			copy: {
				scripts: {
					files: [
						{ // JS.
							expand: true,
							cwd: 'js/src/',
							src: '**',
							dest: 'js/dist/'
						}
					]
				},
				assets: {
					files: [
						{ // Flot - http://www.flotcharts.org/.
							expand: true,
							cwd: 'node_modules/Flot/',
							src: [
								'jquery.flot.js',
								'jquery.flot.time.js',
								'jquery.flot.resize.js'
							],
							dest: 'assets/flot'
						},
						{ // accounting.js - http://openexchangerates.github.io/accounting.js/.
							expand: true,
							cwd: 'node_modules/accounting/',
							src: 'accounting.js',
							dest: 'assets/accounting'
						},
						{ // Tippy.js - https://atomiks.github.io/tippyjs/.
							expand: true,
							cwd: 'node_modules/tippy.js/dist/',
							src: 'tippy.all.js',
							dest: 'assets/tippy.js/'
						}
					]
				}
			},

			// SASS.
			sass: {
				options: {
					style: 'expanded'
				},
				build: {
					files: [ {
						expand: true,
						cwd: 'scss',
						src: '*.scss',
						dest: 'css',
						ext: '.css'
					} ]
				}
			},

			// PostCSS.
			postcss: {
				options: {
					map: false
				},
				prefix: {
					options: {
						processors: [
							require( 'autoprefixer' )(),
							require( 'postcss-eol' )()
						]
					},
					files: [ {
						expand: true,
						cwd: 'css/',
						src: '*.css',
						dest: 'css/'
					} ]
				},
				min: {
					options: {
						processors: [
							require( 'cssnano' )(),
							require( 'postcss-eol' )()
						]
					},
					files: [ {
						expand: true,
						cwd: 'css/',
						src: [
							'*.css',
							'!*.min.css'
						],
						dest: 'css/',
						ext: '.min.css'
					} ]
				}
			},

			// Uglify.
			uglify: {
				scripts: {
					files: {
						// Pronamic Pay.
						'js/dist/admin.min.js': 'js/dist/admin.js',
						'js/dist/admin-reports.min.js': 'js/dist/admin-reports.js',
						'js/dist/admin-tour.min.js': 'js/dist/admin-tour.js',
						// Accounting.
						'assets/accounting/accounting.min.js': 'assets/accounting/accounting.js',
						// Flot.
						'assets/flot/jquery.flot.min.js': 'assets/flot/jquery.flot.js',
						'assets/flot/jquery.flot.resize.min.js': 'assets/flot/jquery.flot.resize.js',
						'assets/flot/jquery.flot.time.min.js': 'assets/flot/jquery.flot.time.js',
						// Tippy.js.
						'assets/tippy.js/tippy.all.min.js': 'assets/tippy.js/tippy.all.js'
					}
				}
			},

			// Clean.
			clean: {
				assets: {
					src: [
						'assets',
						'css',
						'images',
						'js/dist'
					]
				}
			},

			// Webfont.
			webfont: {
				icons: {
					src: 'fonts/src/images/*.svg',
					dest: 'fonts/dist',
					options: {
						font: 'pronamic-pay-icons',
						fontFamilyName: 'Pronamic Pay Icons',
						normalize: true,
						stylesheets: [ 'css' ],
						templateOptions: {
							baseClass: 'pronamic-pay-icon',
							classPrefix: 'pronamic-pay-icon-'
						},
						types: [ 'eot', 'woff2', 'woff', 'ttf', 'svg' ],
						fontHeight: 768,
						customOutputs: [ {
							template: 'fonts/src/templates/variables.scss',
							dest: 'fonts/src/_variables.scss'
						} ]
					}
				}
			},

			// Sass Lint.
			sasslint: {
				options: {
					configFile: '.sass-lint.yml'
				},
				target: [
					'scss/**/*.scss'
				]
			},

			// JSHint.
			jshint: {
				all: [ 'Gruntfile.js', 'composer.json', 'package.json' ]
			},

			// PHPLint.
			phplint: {
				all: [ 'src/**/*.php' ]
			},

			// PHP Mess Detector.
			phpmd: {
				application: {
					dir: 'src'
				},
				options: {
					bin: 'vendor/bin/phpmd',
					exclude: 'node_modules',
					reportFormat: 'text',
					rulesets: 'phpmd.ruleset.xml'
				}
			}
		}
	);

	// Default task(s).
	grunt.registerTask( 'default', [ 'jshint', 'phplint', 'phpmd' ] );
	grunt.registerTask( 'assets', [ 'sasslint', 'sass', 'postcss', 'copy:scripts', 'copy:assets' ] );
	grunt.registerTask( 'min', [ 'uglify' ] );

	grunt.registerTask( 'build_assets', [
		'clean:assets',
		'assets',
		'min'
	] );
};
