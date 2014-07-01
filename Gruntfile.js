module.exports = function(grunt) {

	grunt.initConfig({
	  include_bootstrap: {
	    files: {
	      'dest/styles.css': 'less/manifest.less',
	    },
	  },
	});

	grunt.loadNpmTasks('grunt-include-bootstrap');

	grunt.registerTask('default', ['include_bootstrap']);

};