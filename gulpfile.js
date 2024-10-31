var gulp = require('gulp');
var sass = require('gulp-sass');
var cleanCSS = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');
var rename = require("gulp-rename");
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var zip = require('gulp-zip');
var clean = require('gulp-clean');


//sass theme style
gulp.task('sass:theme',function(){
    gulp.src('./src/sass/plugin.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            includePaths: ['./node_modules/susy/sass', './node_modules/compass-mixins/lib', './node_modules/breakpoint-sass/stylesheets']
        }).on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(rename({
            suffix: ".min",
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./css/frontend'));

    gulp.src('./src/sass/admin.scss')
        .pipe(sourcemaps.init())
        .pipe(sass({
            includePaths: ['./node_modules/susy/sass', './node_modules/compass-mixins/lib', './node_modules/breakpoint-sass/stylesheets']
        }).on('error', sass.logError))
        .pipe(cleanCSS())
        .pipe(rename({
            suffix: ".min",
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('./css/admin'));
});

gulp.task('js:admin', function() {
    gulp.src(['./src/js/admin/**/*.js'])
        .pipe(sourcemaps.init())
        .pipe(concat('admin.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js/admin'));
});

gulp.task('js:frontend', function() {
    gulp.src(['./src/js/frontend/*.js'])
        .pipe(sourcemaps.init())
        .pipe(concat('frontend.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./js/frontend'));
});

gulp.task('watch',function(){
    console.log('start watching');
    gulp.watch('./src/sass/**/*.scss', ['sass:theme']);
    gulp.watch(['./src/js/admin/**/*.js'], ['js:admin']);
    gulp.watch(['./src/js/frontend/*.js'], ['js:frontend']);
});

gulp.task('default',['js:admin','js:frontend','sass:theme']);