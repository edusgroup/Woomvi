"use strict";
/* global require*/

var gulp = require("gulp");

var sass = require("gulp-sass");
gulp.task("sass", function () {
  gulp.src("./public/res/sass/src/**/*.scss")
    .pipe(sass().on("error", sass.logError))
    .pipe(gulp.dest("./public/res/sass/bin/"));
});


var bower = require("gulp-bower");
gulp.task("bower", function() {
    return bower({ cmd: "update"});
});

//http://habrahabr.ru/post/227945/s
var spritesmith = require("gulp.spritesmith");
gulp.task("sprite", function () {
  var spriteData = gulp.src("./public/res/sprite/src/*.png").pipe(spritesmith({
    imgName: "sprite.png",
    cssName: "sprite.scss",
    imgPath: "/res/sprite/build/sprite.png"
  }));
  return spriteData.pipe(gulp.dest("./public/res/sprite/build/"));
});