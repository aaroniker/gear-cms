var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var plumber = require('gulp-plumber');

var errorHandler = function(error) {
    this.emit('end');
    return console.error(error.toString());
};

gulp.task('default', ['styles']);

gulp.task('styles', function(){
    gulp.src('**/modules/**/style.scss')
        .pipe(plumber(errorHandler))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(rename(function(file) {
            file.dirname = file.dirname.replace('scss', 'css');
        }))
        .pipe(gulp.dest('./dist'));
});

gulp.task('watch', function(cb) {
    gulp.watch('**/modules/**/*.scss', ['styles']);
});
