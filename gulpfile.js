var gulp = require('gulp');

var sass = require('gulp-sass');
gulp.task('sass', function () {
  gulp.src('./public/res/sass/src/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./public/res/sass/bin/'));
});


var bower = require('gulp-bower');
gulp.task('bower', function() {
    return bower({ cmd: 'update'})
});


var spritesmith = require('gulp.spritesmith');
gulp.task('sprite', function () {
  var spriteData = gulp.src('./public/res/sprite/src/*.png').pipe(spritesmith({
    imgName: 'sprite.png',
    cssName: 'sprite.css'
  }));
  return spriteData.pipe(gulp.dest('./public/res/sprite/build/'));
});