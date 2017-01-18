var gulp = require('gulp');
var phpunit = require('gulp-phpunit');
var plumber = require('gulp-plumber');
var notify = require('gulp-notify');

gulp.task('test', function() {
	var options = {
		clear: true,
		debug: false, 
		notify: true
	};

    gulp.src('phpunit.xml')
    	.pipe(plumber())
        .pipe(phpunit('./vendor/bin/phpunit', options))
        .on('error', notify.onError({
            title: 'PHPUnit Failed',
            message: 'One or more tests failed.'
        }))
        .pipe(notify({
            title: 'PHPUnit Passed',
            message: 'All tests passed!'
        }));
});

gulp.task('watch', function() {
    gulp.watch(['tests/**/*.php', 'src/**/*.php'], ['test']);
});

gulp.task('default', ['test', 'watch']);