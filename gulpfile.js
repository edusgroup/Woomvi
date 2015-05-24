var gulp = require('gulp');

var sass = require('gulp-sass');

gulp.task('sass', function () {
  gulp.src('./public/res/sass/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/res/css/'));
});