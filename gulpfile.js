var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');

gulp.task('default', ['styles']);

gulp.task('styles', function() {
    gulp.src(['**/modules/**/style.scss'])
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .pipe(rename(function(file) {
            file.dirname += "/dist";
        }))
        .pipe(gulp.dest('.'))
});

gulp.task('watch', function(cb) {
    gulp.watch('**/modules/**/*.scss', ['styles']);
});
