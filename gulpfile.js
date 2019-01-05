var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');

gulp.task('styles', function() {
    return gulp.src(['**/modules/**/style.scss']).pipe(sass({
        outputStyle: 'compressed'
    })).pipe(rename(function(file) {
        file.dirname += "/dist";
    })).pipe(gulp.dest('.'))
});

gulp.task('watch', function(cb) {
    return gulp.watch('**/modules/**/*.scss', gulp.series('styles'));
});

gulp.task('default', gulp.series('styles'));
