module.exports = function (grunt) {
  grunt.initConfig({
    cssmin: {
      options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1
      },
      target: {
        files: [{
          expand: true,
          cwd: 'assets/css',
          src: ['*.css', '!*.min.css'],
          dest: 'frontend/web/css',
          ext: '.min.css'
        },
          {
            'frontend/web/css/all.min.css': ['frontend/web/css/bootstrap.min.css', 'frontend/web/css/site.min.css']
          }
        ],
      }
    },
    concat_sourcemap: {
      options: {
        sourcesContent: true
      },
      all: {
        files: {
          'frontend/web/js/all.js': grunt.file.readJSON('assets/js/all.json')
        }
      }
    },
    uglify: {
      options: {
        mangle: false
      },
      all: {
        files: {
          'frontend/web/js/all.min.js': 'frontend/web/js/all.js'
        }
      }
    },
    watch: {
      js: {
        files: ['assets/js/**/*.js', 'assets/js/all.json'],
        tasks: ['concat_sourcemap', 'uglify:lib'],
        options: {
          livereload: true
        }
      },
      less: {
        files: ['assets/less/**/*.less'],
        tasks: ['less'],
        options: {
          livereload: true
        }
      },
    }
  });

  // Plugin loading
  grunt.loadNpmTasks('grunt-typescript');
  grunt.loadNpmTasks('grunt-concat-sourcemap');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Task definition
  grunt.registerTask('build', ['cssmin', 'less', 'typescript', 'copy', 'concat_sourcemap', 'uglify']);
  grunt.registerTask('default', ['watch']);
};
