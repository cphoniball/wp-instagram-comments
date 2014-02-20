module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			scripts: {

			},
			styles: {

			}
		},
		sass: {
			prod: {
						}
		},
		jshint: {

		},
		uglify: {
			options: {
				mangle: false
			},
			prod: {

			}
		},
		cssmin: {
			prod: {

			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-contrib-cssmin');

	grunt.registerTask('default', ['']);
};
